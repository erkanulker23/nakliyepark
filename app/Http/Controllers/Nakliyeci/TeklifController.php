<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\UserNotification;
use App\Notifications\TeklifReceivedNotification;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

class TeklifController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $teklifler = $company->teklifler()->with('ihale.user')->latest()->paginate(20);
        return view('nakliyeci.teklifler.index', compact('company', 'teklifler'));
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;
        if (! $company || ! $company->isApproved()) {
            return back()->with('error', 'Firmanız henüz onaylı değil.');
        }
        if ($company->isBlocked()) {
            return back()->with('error', 'Firmanız engellenmiştir. Teklif veremezsiniz.');
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
        return back()->with('success', 'Teklifiniz gönderildi.');
    }
}
