<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Dispute;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\UserNotification;
use App\Notifications\ContactMessageToCompanyNotification;
use App\Notifications\TeklifAcceptedNotification;
use Illuminate\Http\Request;

class IhaleController extends Controller
{
    public function show(Request $request, Ihale $ihale)
    {
        $this->authorize('view', $ihale);
        $ihale->load(['teklifler.company.user', 'photos']);
        $acceptedTeklif = $ihale->acceptedTeklif;
        if ($acceptedTeklif) {
            $acceptedTeklif->load(['contactMessages.fromUser', 'company']);
        }
        return view('musteri.ihaleler.show', compact('ihale', 'acceptedTeklif'));
    }

    public function acceptTeklif(Request $request, Ihale $ihale, Teklif $teklif)
    {
        $this->authorize('view', $ihale);
        if ($teklif->ihale_id !== $ihale->id) {
            abort(404);
        }
        if ($ihale->status === 'closed') {
            return back()->with('error', 'Bu ihale zaten kapatılmış.');
        }
        if ($teklif->status === 'accepted') {
            return back()->with('info', 'Bu teklif zaten kabul edilmiş.');
        }
        \DB::transaction(function () use ($ihale, $teklif) {
            $ihale->teklifler()->where('id', '!=', $teklif->id)->update(['status' => 'rejected']);
            $teklif->update(['status' => 'accepted', 'accepted_at' => now()]);
            $ihale->update(['status' => 'closed']);
        });
        \App\Models\AuditLog::log('teklif_accepted', Teklif::class, (int) $teklif->id, null, ['ihale_id' => $ihale->id, 'company_id' => $teklif->company_id]);
        if ($teklif->company && $teklif->company->user) {
            UserNotification::notify(
                $teklif->company->user,
                'teklif_accepted',
                "{$ihale->from_city} → {$ihale->to_city} ihalesinde teklifiniz kabul edildi.",
                'Teklifiniz kabul edildi',
                ['url' => route('nakliyeci.teklifler.index')]
            );
            \App\Services\SafeNotificationService::sendToUser($teklif->company->user, new TeklifAcceptedNotification($ihale, $teklif), 'teklif_accepted');
        }
        return redirect()->route('musteri.ihaleler.show', $ihale)->with('success', 'Teklif kabul edildi. Firma ile iletişime geçebilirsiniz.');
    }

    /** Teklifi reddet (gerekçe isteğe bağlı, firmaya iletilebilir) */
    public function rejectTeklif(Request $request, Ihale $ihale, Teklif $teklif)
    {
        $this->authorize('view', $ihale);
        if ($teklif->ihale_id !== $ihale->id) {
            abort(404);
        }
        if ($teklif->status !== 'pending') {
            return back()->with('error', 'Bu teklif zaten kabul veya reddedilmiş.');
        }
        $request->validate([
            'reject_reason' => 'nullable|string|max:1000',
        ]);

        $teklif->update([
            'status' => 'rejected',
            'reject_reason' => $request->filled('reject_reason') ? $request->reject_reason : null,
        ]);

        \App\Models\AuditLog::log('teklif_rejected', Teklif::class, (int) $teklif->id, null, ['ihale_id' => $ihale->id, 'company_id' => $teklif->company_id]);

        return redirect()->route('musteri.ihaleler.show', $ihale)->with('success', 'Teklif reddedildi.');
    }

    /** Teklif kabulünü geri al (sadece kabulden sonra 10 dakika içinde) */
    public function undoAcceptTeklif(Request $request, Ihale $ihale, Teklif $teklif)
    {
        $this->authorize('view', $ihale);
        if ($teklif->ihale_id !== $ihale->id || $teklif->status !== 'accepted') {
            abort(404);
        }
        if (! $teklif->canUndoAccept()) {
            return back()->with('error', 'Kabul geri alınamaz. Süre (' . Teklif::ACCEPT_UNDO_MINUTES . ' dakika) doldu.');
        }
        \DB::transaction(function () use ($ihale, $teklif) {
            $teklif->update(['status' => 'pending', 'accepted_at' => null]);
            $ihale->update(['status' => 'published']);
        });
        return redirect()->route('musteri.ihaleler.show', $ihale)->with('success', 'Teklif kabulü geri alındı. İhaleniz tekrar yayında.');
    }

    public function storeContactMessage(Request $request, Ihale $ihale, Teklif $teklif)
    {
        $this->authorize('view', $ihale);
        if ($teklif->ihale_id !== $ihale->id || $teklif->status !== 'accepted') {
            abort(404);
        }
        $request->validate(['message' => 'required|string|max:2000']);

        $contactMessage = ContactMessage::create([
            'ihale_id' => $ihale->id,
            'teklif_id' => $teklif->id,
            'from_user_id' => $request->user()->id,
            'company_id' => $teklif->company_id,
            'message' => $request->message,
        ]);

        if ($teklif->company && $teklif->company->user) {
            \App\Services\SafeNotificationService::sendToUser($teklif->company->user, new ContactMessageToCompanyNotification($contactMessage), 'contact_message_to_company');
        }

        return back()->with('success', 'Mesajınız firmaya iletildi. Firma sizinle iletişime geçecektir.');
    }

    /** Uyuşmazlık / şikâyet aç (kapalı ihale, kabul edilmiş teklif için) */
    public function storeDispute(Request $request, Ihale $ihale)
    {
        $this->authorize('view', $ihale);
        $acceptedTeklif = $ihale->acceptedTeklif;

        if (! $acceptedTeklif) {
            return back()->with('error', 'Şikâyet açabilmek için önce bir teklifi kabul etmiş olmanız gerekir. Henüz kabul ettiğiniz bir teklif yok.');
        }
        if ($ihale->status !== 'closed') {
            return back()->with('error', 'Şikâyet yalnızca ihale kapandıktan sonra (teklif kabul edildikten sonra) açılabilir. Bu ihale henüz kapalı görünmüyor; lütfen sayfayı yenileyin veya destek ile iletişime geçin.');
        }

        $request->validate([
            'reason' => 'required|string|in:iptal,adres_hatasi,gelmedi,hakaret,diger',
            'description' => 'nullable|string|max:2000',
        ]);
        if (Dispute::where('ihale_id', $ihale->id)->where('opened_by_user_id', $request->user()->id)->whereIn('status', ['open', 'admin_review'])->exists()) {
            return back()->with('error', 'Bu ihale için zaten açık bir uyuşmazlık kaydınız var. Aynı ihale için birden fazla şikâyet açılamaz.');
        }
        Dispute::create([
            'ihale_id' => $ihale->id,
            'company_id' => $acceptedTeklif->company_id,
            'opened_by_user_id' => $request->user()->id,
            'opened_by_type' => 'musteri',
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'open',
        ]);
        \App\Services\AdminNotifier::notify('dispute_opened', "Uyuşmazlık açıldı: {$ihale->from_city} → {$ihale->to_city} (Müşteri)", 'Yeni uyuşmazlık', ['url' => route('admin.disputes.index')]);
        return redirect()->route('musteri.ihaleler.show', $ihale)->with('success', 'Şikâyetiniz alındı. En kısa sürede incelenecektir.');
    }

    /** Müşteri ihale kapatır (yayından kaldırır; bekleyen teklifler reddedilir). Sadece yayındaki ve henüz teklif kabul edilmemiş ihaleler. */
    public function close(Request $request, Ihale $ihale)
    {
        $this->authorize('view', $ihale);
        if ($ihale->status !== 'published') {
            return back()->with('error', $ihale->status === 'closed' ? 'Bu ihale zaten kapalı.' : 'Sadece yayındaki ihaleler kapatılabilir.');
        }
        if ($ihale->acceptedTeklif) {
            return back()->with('error', 'Teklif kabul edilmiş ihale zaten kapalı sayılır; ek işlem gerekmez.');
        }
        \DB::transaction(function () use ($ihale) {
            \App\Models\Teklif::where('ihale_id', $ihale->id)->where('status', 'pending')->update(['status' => 'rejected']);
            $ihale->update(['status' => 'closed']);
        });
        return back()->with('success', 'İhale kapatıldı. Artık firmalar bu ilana teklif veremez.');
    }

    /** Müşteri ihale açar (yayına alır). Kapalı (manuel kapatılmış) veya taslaktaki ihaleler. */
    public function open(Request $request, Ihale $ihale)
    {
        $this->authorize('view', $ihale);
        if ($ihale->status === 'published') {
            return back()->with('info', 'Bu ihale zaten yayında.');
        }
        if ($ihale->status === 'closed' && $ihale->acceptedTeklif) {
            return back()->with('error', 'Teklif kabul edilmiş ihale tekrar açılamaz. Kabulü geri almak için ihale detayından «Kabulü geri al» kullanın.');
        }
        if (! in_array($ihale->status, ['closed', 'draft'], true)) {
            return back()->with('error', 'Sadece kapalı veya bekleme modundaki ihaleler yayına alınabilir. (Onay bekleyen ihaleler admin onayından sonra yayına alınır.)');
        }
        $ihale->update(['status' => 'published']);
        return back()->with('success', 'İhale yayına alındı. Firmalar tekrar teklif verebilir.');
    }

    /** Müşteri ihale bekleme moduna alır (taslak; listede görünmez, teklif kabul edilmez). Sadece yayındaki ihaleler. */
    public function pause(Request $request, Ihale $ihale)
    {
        $this->authorize('view', $ihale);
        if ($ihale->status !== 'published') {
            return back()->with('error', $ihale->status === 'draft' ? 'Bu ihale zaten bekleme modunda.' : 'Sadece yayındaki ihaleler bekleme moduna alınabilir.');
        }
        if ($ihale->acceptedTeklif) {
            return back()->with('error', 'Teklif kabul edilmiş ihale bekleme moduna alınamaz.');
        }
        $ihale->update(['status' => 'draft']);
        return back()->with('success', 'İhale bekleme moduna alındı. İstediğiniz zaman «Yayına al» ile tekrar açabilirsiniz.');
    }
}
