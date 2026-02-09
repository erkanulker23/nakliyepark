<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Hesabınız engellenmiştir.');
        }

        if (BlockedIp::isBlocked($request->ip())) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Erişim engellenmiştir.');
        }

        if ($user->phone && BlockedPhone::isBlocked($user->phone)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Hesabınız engellenmiştir.');
        }

        return $next($request);
    }
}
