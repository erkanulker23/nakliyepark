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
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        return view('nakliyeci.paketler.index', compact('company', 'paketler'));
    }
}
