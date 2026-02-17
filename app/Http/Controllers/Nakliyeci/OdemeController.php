<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Services\IyzicoPaymentService;
use Illuminate\Http\Request;

class OdemeController extends Controller
{
    /** Borç ödemesi: checkout form başlat, iyzico sayfasına yönlendir. */
    public function startBorc(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        if (! IyzicoPaymentService::isEnabled()) {
            return redirect()->route('nakliyeci.borc.index')->with('error', 'Ödeme alımı şu an kapalı.');
        }
        $amount = $company->outstanding_commission;
        if ($amount <= 0) {
            return redirect()->route('nakliyeci.borc.index')->with('info', 'Ödenecek borcunuz bulunmuyor.');
        }
        $result = IyzicoPaymentService::initializeBorcPayment($company, $amount);
        if (! $result['success']) {
            return redirect()->route('nakliyeci.borc.index')->with('error', $result['error'] ?? 'Ödeme başlatılamadı.');
        }
        return view('nakliyeci.odeme.checkout', [
            'checkout_form_content' => $result['checkout_form_content'],
            'payment_page_url' => $result['payment_page_url'] ?? null,
        ]);
    }

    /** Paket satın alma: checkout form başlat. */
    public function startPackage(Request $request)
    {
        $request->validate(['package_id' => 'required|string|max:50']);
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        if (! IyzicoPaymentService::isEnabled()) {
            return redirect()->route('nakliyeci.paketler.index')->with('error', 'Paket satın alma şu an kapalı.');
        }
        $defaults = config('nakliyepark.nakliyeci_paketler', []);
        $stored = \App\Models\Setting::get('nakliyeci_paketler', '');
        if ($stored) {
            $paketler = is_string($stored) ? json_decode($stored, true) : $stored;
        } else {
            $paketler = $defaults;
        }
        $paket = null;
        foreach ($paketler as $p) {
            if (($p['id'] ?? '') === $request->package_id) {
                $paket = $p;
                break;
            }
        }
        if (! $paket || empty($paket['price'])) {
            return redirect()->route('nakliyeci.paketler.index')->with('error', 'Geçersiz paket.');
        }
        $amount = (float) $paket['price'];
        $name = $paket['name'] ?? $request->package_id;
        $result = IyzicoPaymentService::initializePackagePayment($company, $request->package_id, $amount, $name);
        if (! $result['success']) {
            return redirect()->route('nakliyeci.paketler.index')->with('error', $result['error'] ?? 'Ödeme başlatılamadı.');
        }
        return view('nakliyeci.odeme.checkout', [
            'checkout_form_content' => $result['checkout_form_content'],
            'payment_page_url' => $result['payment_page_url'] ?? null,
        ]);
    }

    /** iyzico callback: token ile sonucu al, kaydet, yönlendir. */
    public function callback(Request $request)
    {
        $token = $request->input('token') ?? $request->query('token');
        if (! $token) {
            return redirect()->route('nakliyeci.dashboard')->with('error', 'Ödeme sonucu alınamadı.');
        }
        $result = IyzicoPaymentService::retrieveAndComplete($token);
        if ($result['success']) {
            return redirect($result['redirect_url'])->with('success', 'Ödemeniz alındı. Teşekkür ederiz.');
        }
        return redirect()->route('nakliyeci.dashboard')->with('error', $result['error'] ?? 'Ödeme işlemi başarısız.');
    }
}
