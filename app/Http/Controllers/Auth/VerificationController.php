<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * E-posta doğrulama bilgi sayfası (doğrulanmamış kullanıcılar buraya yönlendirilebilir).
     */
    public function notice(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasVerifiedEmail()) {
            return $user->isNakliyeci()
                ? redirect()->route('nakliyeci.dashboard')
                : ($user->isMusteri() ? redirect()->route('musteri.dashboard') : redirect('/'));
        }
        return view('auth.verify');
    }

    /**
     * Doğrulama linkine tıklandığında (imzalı URL ile).
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectAfterVerify($request->user(), true);
        }

        $request->fulfill();

        return $this->redirectAfterVerify($request->user(), false);
    }

    private function redirectAfterVerify($user, bool $alreadyVerified): \Illuminate\Http\RedirectResponse
    {
        $message = $alreadyVerified ? 'E-posta adresiniz zaten doğrulanmış.' : 'E-posta adresiniz doğrulandı.';
        if ($user->isNakliyeci()) {
            return redirect()->route('nakliyeci.dashboard')->with('success', $message);
        }
        if ($user->isMusteri()) {
            return redirect()->route('musteri.dashboard')->with('success', $message);
        }
        return redirect('/')->with('success', $message);
    }

    /**
     * Doğrulama e-postasını tekrar gönder.
     */
    public function resend(Request $request)
    {
        $request->validate(['email' => 'sometimes|email']);

        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('success', 'Doğrulama linki e-posta adresinize tekrar gönderildi.');
        }

        return back()->with('info', 'E-posta adresiniz zaten doğrulanmış.');
    }
}
