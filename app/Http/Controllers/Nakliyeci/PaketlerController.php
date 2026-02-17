<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\IyzicoPaymentService;
use Illuminate\Http\Request;

class PaketlerController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $stored = Setting::get('nakliyeci_paketler', '');
        $paketler = $stored ? (is_string($stored) ? json_decode($stored, true) : $stored) : config('nakliyepark.nakliyeci_paketler', []);
        $paymentEnabled = IyzicoPaymentService::isEnabled();
        return view('nakliyeci.paketler.index', compact('company', 'paketler', 'paymentEnabled'));
    }
}
