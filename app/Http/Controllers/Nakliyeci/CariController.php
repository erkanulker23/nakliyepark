<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CariController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $isler = $company->acceptedTeklifler()->with('ihale')->latest()->paginate(20);
        $toplamKazanc = $company->total_earnings;
        $toplamKomisyon = $company->total_commission;
        $netKazanc = $toplamKazanc - $toplamKomisyon;
        return view('nakliyeci.cari.index', compact('company', 'isler', 'toplamKazanc', 'toplamKomisyon', 'netKazanc'));
    }
}
