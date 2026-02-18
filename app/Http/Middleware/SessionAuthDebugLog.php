<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Oturum/auth tutarsızlığını bulmak için: her istekte auth durumunu ve session bilgisini loglar.
 * Sadece APP_DEBUG=true veya SESSION_DEBUG=true iken çalışır.
 */
class SessionAuthDebugLog
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.debug') || config('session.debug', false)) {
            $session = $request->session();
            $sessionId = $session->getId();
            $sessionIdShort = $sessionId ? substr(md5($sessionId), 0, 8) : 'none';
            $authCheck = Auth::check();
            $userId = $authCheck ? Auth::id() : null;
            $role = $authCheck && Auth::user() ? Auth::user()->role : null;
            $cookieName = $session->getName();
            Log::channel('single')->info('SessionAuthDebug', [
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'auth_check' => $authCheck,
                'user_id' => $userId,
                'role' => $role,
                'session_id_hash' => $sessionIdShort,
                'has_cookie' => $request->hasCookie($cookieName),
            ]);
        }

        return $next($request);
    }
}
