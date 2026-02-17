<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        if ($user) {
            $user->refresh();
            if ($user->hasVerifiedEmail()) {
                return $user->isNakliyeci()
                    ? redirect()->route('nakliyeci.dashboard')
                    : ($user->isMusteri() ? redirect()->route('musteri.dashboard') : redirect('/'));
            }
        }
        return view('auth.verify');
    }

    /**
     * Doğrulama linkine tıklandığında (imzalı URL ile). Giriş yapmadan token ile onaylanır.
     */
    public function verify(Request $request, string $id, string $hash)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->route('login')->with('error', 'Geçersiz doğrulama linki.');
        }
        if ($user->hasVerifiedEmail()) {
            return $this->redirectAfterVerify($user, true);
        }
        if (! hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Geçersiz doğrulama linki.');
        }

        $user->markEmailAsVerified();

        // Giriş yapmamışsa token ile doğrulama sonrası oturum aç (isteğe bağlı, UX için)
        if (! Auth::check()) {
            Auth::login($user);
        }

        return $this->redirectAfterVerify($user, false);
    }

    private function redirectAfterVerify($user, bool $alreadyVerified): \Illuminate\Http\RedirectResponse
    {
        $message = $alreadyVerified ? 'E-posta adresiniz zaten doğrulanmış.' : 'E-postanız doğrulanmıştır.';
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
