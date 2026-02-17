<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Services\IyzicoPaymentService;
use Illuminate\Http\Request;

class BorcController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $komisyonBorcu = $company->outstanding_commission;
        $paymentEnabled = IyzicoPaymentService::isEnabled();
        return view('nakliyeci.borc.index', compact('company', 'komisyonBorcu', 'paymentEnabled'));
    }
}
