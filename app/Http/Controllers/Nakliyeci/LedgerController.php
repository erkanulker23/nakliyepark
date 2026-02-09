<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\YukIlani;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    /** Firma başına en fazla aktif defter ilanı (istismar önlemi) */
    public const MAX_AKTIF_ILAN = 300;
    public function index(Request $request)
    {
        $ilanlar = YukIlani::with('company')
            ->where('status', 'active')
            ->where('company_id', '!=', $request->user()->company?->id ?? 0)
            ->latest()
            ->paginate(15);

        return view('nakliyeci.ledger', compact('ilanlar'));
    }

    public function create(Request $request)
    {
        $company = $request->user()->company;
        if (!$company || !$company->isApproved()) {
            return redirect()->route('nakliyeci.dashboard')->with('error', 'Önce onaylı bir firma oluşturmalısınız.');
        }
        $aktifSayisi = $company->yukIlanlari()->where('status', 'active')->count();
        if ($aktifSayisi >= self::MAX_AKTIF_ILAN) {
            return redirect()->route('nakliyeci.ledger')->with('error', 'Firmanızın aktif defter ilanı limitine (' . self::MAX_AKTIF_ILAN . ') ulaştınız. Eski ilanları pasife alıp tekrar deneyin.');
        }
        return view('nakliyeci.ledger-create');
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;
        if (!$company || !$company->isApproved()) {
            return redirect()->route('nakliyeci.dashboard')->with('error', 'Önce onaylı bir firma oluşturmalısınız.');
        }
        $aktifSayisi = $company->yukIlanlari()->where('status', 'active')->count();
        if ($aktifSayisi >= self::MAX_AKTIF_ILAN) {
            return redirect()->back()->withInput()->with('error', 'Firmanızın aktif defter ilanı limitine (' . self::MAX_AKTIF_ILAN . ') ulaştınız. Yeni ilan ekleyemezsiniz.');
        }
        $data = $request->validate([
            'from_city' => 'required|string|max:100',
            'to_city' => 'required|string|max:100',
            'load_type' => 'nullable|string|max:50',
            'load_date' => 'nullable|date',
            'volume_m3' => 'nullable|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $data['company_id'] = $company->id;
        $data['status'] = 'active';
        YukIlani::create($data);
        return redirect()->route('nakliyeci.ledger')->with('success', 'Deftere yazıldı. İlanınız yayında.');
    }
}
