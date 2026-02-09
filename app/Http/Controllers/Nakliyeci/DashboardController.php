<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Ihale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }

        $teklifler = $company->teklifler()->with('ihale.user')->latest()->paginate(15);
        $yayindakiIhaleler = Ihale::where('status', 'published')->latest()->take(20)->get();

        return view('nakliyeci.dashboard', compact('company', 'teklifler', 'yayindakiIhaleler'));
    }
}
