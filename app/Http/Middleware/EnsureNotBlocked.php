<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $this->logout($request, 'user_blocked');

            return redirect()->route('login')->with('error', 'Hesabınız engellenmiştir.');
        }

        $ip = $request->ip();
        // Local'de localhost asla IP engeliyle çıkış yaptırma (yanlış engel / test)
        if (! app()->environment('local') || ! in_array($ip, ['127.0.0.1', '::1'], true)) {
            if (BlockedIp::isBlocked($ip)) {
                $this->logout($request, 'ip_blocked', ['ip' => $ip]);

                return redirect()->route('login')->with('error', 'Erişim engellenmiştir.');
            }
        }

        if ($user->phone && BlockedPhone::isBlocked($user->phone)) {
            $this->logout($request, 'phone_blocked');

            return redirect()->route('login')->with('error', 'Hesabınız engellenmiştir.');
        }

        return $next($request);
    }

    private function logout(Request $request, string $reason, array $context = []): void
    {
        if (config('app.debug') || config('session.debug', false)) {
            Log::info('EnsureNotBlocked: session invalidated', array_merge([
                'reason' => $reason,
                'user_id' => Auth::id(),
                'path' => $request->path(),
            ], $context));
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
