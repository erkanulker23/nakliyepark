<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\UserNotification;
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
        $request->validate([
            'ihale_id' => 'required|exists:ihaleler,id',
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:500',
        ]);
        $ihale = Ihale::where('id', $request->ihale_id)->where('status', 'published')->firstOrFail();
        if (Teklif::where('ihale_id', $ihale->id)->where('company_id', $company->id)->exists()) {
            return back()->with('error', 'Bu ihale için zaten teklif verdiniz.');
        }
        Teklif::create([
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
        }
        return redirect()->route('nakliyeci.ihaleler.show', $ihale)->with('success', 'Teklifiniz gönderildi.');
    }
}
