<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerifiedForPanel
{
    /**
     * Musteri ve nakliyeci panelleri için e-posta doğrulama zorunludur.
     * Admin için zorunlu değildir.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        // Test ortamı: bu e-postalar için doğrulama zorunlu değil
        $testEmails = ['firma@nakliyepark.test', 'musteri@nakliyepark.test'];
        $skipVerification = $user->isAdmin() || in_array($user->email, $testEmails, true);

        if ($skipVerification || $user->hasVerifiedEmail()) {
            return $next($request);
        }

        return redirect()->route('verification.notice')
            ->with('warning', 'Panel erişimi için lütfen e-posta adresinizi doğrulayın. Gelen kutunuzu ve istenmeyen posta klasörünüzü kontrol edin.');
    }
}
