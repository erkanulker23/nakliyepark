<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=(), camera=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Tüm sayfalarda tarayıcı önbelleğini kapat (frontend/admin: giriş/çıkış, flash mesaj, güncel içerik)
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Location header'da newline varsa kaldır (log hatası ve yanlış yönlendirme önlemi)
        if ($response->headers->has('Location')) {
            $location = $response->headers->get('Location');
            if ($location !== null && $location !== '') {
                $response->headers->set('Location', preg_replace('/[\r\n\x00]/', '', $location));
            }
        }

        return $response;
    }
}
