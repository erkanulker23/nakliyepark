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
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
            'not.nakliyeci' => \App\Http\Middleware\EnsureNotNakliyeci::class,
            'firmalar.visible' => \App\Http\Middleware\EnsureFirmalarPageVisible::class,
            'verified.panel' => \App\Http\Middleware\EnsureEmailVerifiedForPanel::class,
        ]);
        $middleware->web(append: [
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

        // CSRF token hatası için özel işleme
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'CSRF token süresi doldu. Lütfen sayfayı yenileyip tekrar deneyin.'], 419);
            }
            
            // Login sayfalarına özel yönlendirme
            if ($request->is('yonetici/admin') || $request->is('login')) {
                return redirect()->back()
                    ->withInput($request->except('_token'))
                    ->with('error', 'Oturum süresi doldu. Lütfen tekrar giriş yapın.');
            }
            
            return redirect()->back()
                ->withInput($request->except('_token'))
                ->with('error', 'Sayfa süresi doldu. Lütfen sayfayı yenileyip tekrar deneyin.');
        });
    })->create();
