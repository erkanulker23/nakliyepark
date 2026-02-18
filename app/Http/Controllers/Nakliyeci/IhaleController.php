<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\UserNotification;
use App\Notifications\TeklifReceivedNotification;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

class IhaleController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $ihaleler = Ihale::where('status', 'published')->withCount('teklifler')->latest()->paginate(15);
        return view('nakliyeci.ihaleler.index', compact('company', 'ihaleler'));
    }

    public function show(Request $request, Ihale $ihale)
    {
        if ($ihale->status !== 'published') {
            abort(404);
        }
        $company = $request->user()->company;
        if (! $company || ! $company->isApproved()) {
            return redirect()->route('nakliyeci.dashboard')->with('error', 'Firmanız onaylı değil.');
        }
        $ihale->load(['photos', 'user']);
        $nakliyeciVerdiMi = $ihale->teklifler()->where('company_id', $company->id)->exists();
        $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first();
        return view('nakliyeci.ihaleler.show', compact('ihale', 'company', 'nakliyeciVerdiMi', 'benimTeklif'));
    }

    public function storeTeklif(Request $request)
    {
        $company = $request->user()->company;
        if (! $company || ! $company->isApproved()) {
            return back()->with('error', 'Firmanız henüz onaylı değil.');
        }
        if ($company->isBlocked()) {
            return back()->with('error', 'Firmanız engellenmiştir. Teklif veremezsiniz.');
        }
        if (! $company->hasPackage()) {
            return back()->with('error', 'İhalelere fiyat verebilmek için abonelik paketiniz olmalıdır. Paket satın almak için lütfen bizimle iletişime geçin.');
        }
        if (! $company->canSendTeklif()) {
            return back()->with('error', 'Bu ay için teklif limitiniz dolmuştur (' . $company->teklif_limit . ' teklif). Paket yükseltmek için lütfen bizimle iletişime geçin.');
        }
        $request->validate([
            'ihale_id' => 'required|exists:ihaleler,id',
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:500',
        ]);
        $ihale = Ihale::where('id', $request->ihale_id)->where('status', 'published')->firstOrFail();
        if (Teklif::where('ihale_id', $ihale->id)->where('company_id', $company->id)->exists()) {
            return back()->with('error', 'Bu ihale için zaten teklif verdiniz.');
        }
        $teklif = Teklif::create([
            'ihale_id' => $ihale->id,
            'company_id' => $company->id,
            'amount' => $request->amount,
            'message' => $request->message,
            'status' => 'pending',
        ]);
        AdminNotifier::notify('teklif_submitted', "{$company->name} ihaleye teklif verdi: {$ihale->from_city} → {$ihale->to_city} - " . number_format($request->amount, 0, ',', '.') . ' ₺', 'Yeni teklif', ['url' => route('admin.ihaleler.show', $ihale)]);
        if ($ihale->user_id) {
            $ihale->load('user');
            UserNotification::notify(
                $ihale->user,
                'teklif_received',
                "{$company->name} firması ihalenize " . number_format($request->amount, 0, ',', '.') . ' ₺ teklif verdi.',
                'Yeni teklif geldi',
                ['url' => route('musteri.ihaleler.show', $ihale)]
            );
            \App\Services\SafeNotificationService::sendToUser($ihale->user, new TeklifReceivedNotification($ihale, $teklif), 'teklif_received_musteri');
        } elseif ($ihale->guest_contact_email) {
            \App\Services\SafeNotificationService::sendToEmail($ihale->guest_contact_email, new TeklifReceivedNotification($ihale, $teklif), 'teklif_received_guest');
        }
        return redirect()->route('nakliyeci.ihaleler.show', $ihale)->with('success', 'Teklifiniz gönderildi.');
    }

    /** Nakliyeci kendi teklifini güncelleme talebi gönderir; admin onayından sonra uygulanır. */
    public function requestTeklifUpdate(Request $request, Ihale $ihale, Teklif $teklif)
    {
        if ($ihale->status !== 'published') {
            abort(404);
        }
        $company = $request->user()->company;
        if (! $company || $teklif->company_id !== $company->id) {
            abort(403);
        }
        if ($teklif->status !== 'pending') {
            return back()->with('error', 'Sadece beklemedeki teklifler güncellenebilir.');
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:500',
        ]);
        $teklif->update([
            'pending_amount' => $request->amount,
            'pending_message' => $request->message,
        ]);
        AdminNotifier::notify('teklif_update_request', "{$company->name} teklif güncelleme talebi gönderdi: {$ihale->from_city} → {$ihale->to_city} - " . number_format((float) $request->amount, 0, ',', '.') . ' ₺ (onay bekliyor)', 'Teklif güncelleme talebi', ['url' => route('admin.teklifler.edit', $teklif)]);
        $backUrl = $request->input('from_public') ? route('ihaleler.show', $ihale) : route('nakliyeci.ihaleler.show', $ihale);
        return redirect($backUrl)->with('success', 'Güncelleme talebiniz alındı. Admin onayından sonra yansıyacaktır.');
    }
}
