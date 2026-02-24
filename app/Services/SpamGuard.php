<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpamGuard
{
    /**
     * Honeypot alan adı. Botlar bu alanı doldurur; gerçek kullanıcılar görmez.
     * Gönderimde dolu gelirse spam kabul et.
     */
    public const HONEYPOT_FIELD = 'company_website';

    /**
     * Honeypot kontrolü: alan gönderilmiş ve boş değilse true (spam).
     */
    public static function isHoneypotFilled(Request $request): bool
    {
        $value = $request->input(self::HONEYPOT_FIELD);
        if ($value === null) {
            return false;
        }

        return is_string($value) && trim($value) !== '';
    }

    /**
     * Turnstile token'ı Cloudflare API ile doğrula.
     * TURNSTILE_SECRET_KEY yoksa veya boşsa true döner (doğrulama atlanır).
     */
    public static function verifyTurnstile(Request $request): bool
    {
        $secret = config('services.turnstile.secret_key');
        if ($secret === null || $secret === '') {
            return true;
        }

        $token = $request->input('cf-turnstile-response');
        if (! is_string($token) || $token === '') {
            return false;
        }

        $ok = false;
        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            $data = $response->json();
            $ok = $response->successful() && isset($data['success']) && $data['success'] === true;
            if (! $ok) {
                Log::channel('single')->debug('Turnstile verify failed', [
                    'success' => $data['success'] ?? false,
                    'error_codes' => $data['error-codes'] ?? [],
                ]);
            }
        } catch (\Throwable $e) {
            Log::channel('single')->warning('Turnstile verify error: '.$e->getMessage());
        }

        return $ok;
    }

    /**
     * Önce honeypot, sonra Turnstile kontrolü. Spam ise false.
     */
    public static function pass(Request $request): bool
    {
        if (self::isHoneypotFilled($request)) {
            return false;
        }

        return self::verifyTurnstile($request);
    }
}
