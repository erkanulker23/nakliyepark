<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaketlerController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $paketler = [
            ['id' => 'baslangic', 'name' => 'Başlangıç', 'price' => 99, 'teklif_limit' => 50, 'description' => 'Aylık 50 teklif hakkı'],
            ['id' => 'profesyonel', 'name' => 'Profesyonel', 'price' => 249, 'teklif_limit' => 200, 'description' => 'Aylık 200 teklif, öncelikli listeleme'],
            ['id' => 'kurumsal', 'name' => 'Kurumsal', 'price' => 499, 'teklif_limit' => 999, 'description' => 'Sınırsız teklif, reklam desteği'],
        ];
        return view('nakliyeci.paketler.index', compact('company', 'paketler'));
    }
}
