<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Uygulama seviyesi sağlık kontrolü: DB ve cache bağlantısı.
     * Load balancer veya izleme sistemleri için kullanılabilir.
     */
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
        ];
        $healthy = ! in_array(false, $checks, true);
        return response()->json([
            'status' => $healthy ? 'ok' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();
            DB::connection()->getDatabaseName();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function checkCache(): bool
    {
        try {
            $key = 'health_check_' . uniqid();
            Cache::put($key, 1, 10);
            $ok = Cache::get($key) === 1;
            Cache::forget($key);
            return $ok;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
