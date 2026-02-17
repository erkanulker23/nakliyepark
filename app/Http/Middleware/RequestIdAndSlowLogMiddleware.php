<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestIdAndSlowLogMiddleware
{
    /** İstek bu süreyi (saniye) aşarsa yavaş istek olarak loglanır */
    public const SLOW_THRESHOLD_SECONDS = 5.0;

    public function handle(Request $request, Closure $next): Response
    {
        $raw = $request->header('X-Request-ID');
        $sanitized = $raw !== null && $raw !== ''
            ? preg_replace('/[\r\n\x00]/', '', (string) $raw)
            : '';
        $requestId = ($sanitized !== '' && strlen($sanitized) <= 128)
            ? $sanitized
            : Str::uuid()->toString();
        $request->attributes->set('request_id', $requestId);
        Log::shareContext([
            'request_id' => $requestId,
            'user_id' => $request->user()?->id,
        ]);

        $start = microtime(true);
        $response = $next($request);
        $duration = round(microtime(true) - $start, 3);

        $context = [
            'request_id' => $requestId,
            'duration_seconds' => $duration,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => $request->user()?->id,
        ];

        if ($duration >= self::SLOW_THRESHOLD_SECONDS) {
            Log::channel('single')->warning('Yavaş istek tespit edildi', $context);
        }

        $response->headers->set('X-Request-ID', $requestId);
        return $response;
    }
}
