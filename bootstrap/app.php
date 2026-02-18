<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'nakliyeci/odeme/callback',
        ]);
        // Giriş yapmış kullanıcı guest sayfalara (login, register vb.) giderse yönlendir; /yonetici/admin guest değil, orada kontrol controller'da
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $intended = $request->session()->get('url.intended');
            return $intended && $intended !== $request->url() ? $intended : '/';
        });
        // Giriş yapmamış kullanıcı /admin/* isterse admin giriş sayfasına, diğer korumalı sayfalara /login
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            return $request->is('admin', 'admin/*') ? route('admin.login') : route('login');
        });
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
            'not.nakliyeci' => \App\Http\Middleware\EnsureNotNakliyeci::class,
            'firmalar.visible' => \App\Http\Middleware\EnsureFirmalarPageVisible::class,
            'verified.panel' => \App\Http\Middleware\EnsureEmailVerifiedForPanel::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SessionAuthDebugLog::class,
            \App\Http\Middleware\EnsureNotBlocked::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 429 Rate limit: Kullanıcıya anlamlı mesaj göster, form sayfasına yönlendir
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('passwords.throttled', ['seconds' => $e->getHeaders()['Retry-After'] ?? 60]),
                ], 429);
            }
            $seconds = (int) ($e->getHeaders()['Retry-After'] ?? 60);
            $message = __('passwords.throttled', ['seconds' => $seconds]);
            return redirect()->back()
                ->withInput($request->except('password', '_token'))
                ->withErrors(['email' => $message]);
        });

        // CSRF / 419 hatası: TokenMismatchException veya 419 HttpException (Laravel bazen 419’e çeviriyor)
        $handle419 = function (\Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'CSRF token süresi doldu. Lütfen sayfayı yenileyip tekrar deneyin.'], 419);
            }
            // Logout isteğinde token süresi dolmuşsa yine de çıkış yap; mesaj gösterme (zaten oturum kapalı, ana sayfada "giriş yapmadım" hissi yaratmasın)
            if ($request->isMethod('POST') && $request->is('logout')) {
                \Illuminate\Support\Facades\Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/');
            }
            // Admin login (POST): giriş sayfasına taze form ile yönlendir (bilgi mesajı, hata değil)
            if ($request->isMethod('POST') && ($request->routeIs('admin.login.submit') || $request->is('yonetici/admin'))) {
                return redirect()->route('admin.login')
                    ->withInput($request->except('_token'))
                    ->with('info', 'Sayfa yenilendi. Lütfen tekrar giriş yapın.');
            }
            // Normal login (POST)
            if ($request->isMethod('POST') && $request->is('login')) {
                return redirect()->route('login')
                    ->withInput($request->except('_token'))
                    ->with('info', 'Sayfa yenilendi. Lütfen tekrar giriş yapın.');
            }
            // Form gönderimlerinde (ihale, iletişim vb.) 419 hata sayfasını göster — kullanıcı nedenini görsün
            return response()->view('errors.419', [], 419);
        };

        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) use ($handle419) {
            return $handle419($request);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) use ($handle419) {
            if ($e->getStatusCode() === 419) {
                return $handle419($request);
            }
            return null;
        });
    })->create();
