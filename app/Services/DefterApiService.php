<?php

namespace App\Services;

use App\Models\DefterApiEntry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DefterApiService
{
    /**
     * API'den veri çekip defter_api_entries tablosuna yazar veya günceller.
     * all=1&limit=N ile tüm kayıtlar çekilir.
     *
     * @return array{success: bool, message: string, fetched: int, created: int, updated: int, errors?: array}
     */
    public function fetchAndSync(): array
    {
        $url = config('nakliyepark.defter_api.url');
        $cookie = config('nakliyepark.defter_api.cookie');
        $limit = config('nakliyepark.defter_api.fetch_limit', 500);

        if (empty($url)) {
            return [
                'success' => false,
                'message' => 'Defter API URL ayarlanmamış. .env dosyasında DEFTER_API_URL tanımlayın.',
                'fetched' => 0,
                'created' => 0,
                'updated' => 0,
            ];
        }

        $fetchUrl = $url . (str_contains($url, '?') ? '&' : '?') . 'all=1&limit=' . $limit;
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => 'Mozilla/5.0 (compatible; NakliyePark-DefterSync/1.0)',
        ];
        if (! empty($cookie)) {
            $headers['Cookie'] = $cookie;
        }

        try {
            $response = Http::timeout(120)->withHeaders($headers)->get($fetchUrl);
        } catch (\Throwable $e) {
            Log::warning('Defter API fetch exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'API isteği başarısız: ' . $e->getMessage(),
                'fetched' => 0,
                'created' => 0,
                'updated' => 0,
                'errors' => [$e->getMessage()],
            ];
        }

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'API HTTP ' . $response->status() . ' döndü.',
                'fetched' => 0,
                'created' => 0,
                'updated' => 0,
                'errors' => [$response->body()],
            ];
        }

        $body = $response->json();
        if (! is_array($body) || ($body['type'] ?? '') === 'error') {
            return [
                'success' => false,
                'message' => $body['err'] ?? $body['message'] ?? 'API hata yanıtı döndü.',
                'fetched' => 0,
                'created' => 0,
                'updated' => 0,
            ];
        }

        $data = $body['data'] ?? [];
        if (! is_array($data)) {
            $data = [];
        }

        $created = 0;
        $updated = 0;
        foreach ($data as $item) {
            $externalId = (string) ($item['id'] ?? '');
            if ($externalId === '') {
                continue;
            }
            $attrs = $this->mapEntryAttributes($item);
            $entry = DefterApiEntry::query()->where('external_id', $externalId)->first();
            if ($entry) {
                $entry->update($attrs);
                $updated++;
            } else {
                DefterApiEntry::create(array_merge($attrs, ['external_id' => $externalId]));
                $created++;
            }
        }

        return [
            'success' => true,
            'message' => count($data) . ' kayıt işlendi. Yeni: ' . $created . ', Güncellenen: ' . $updated,
            'fetched' => count($data),
            'created' => $created,
            'updated' => $updated,
        ];
    }

    /**
     * API'den gelen tek bir kayıt objesini DefterApiEntry fillable alanlarına map eder.
     */
    private function mapEntryAttributes(array $item): array
    {
        $phone = $item['telefon'] ?? null;
        if (is_string($phone)) {
            $phone = preg_replace('/\s+/', '', $phone);
        } else {
            $phone = null;
        }
        $whatsapp = $item['whatsapp'] ?? null;
        $whatsapp = is_string($whatsapp) ? trim($whatsapp) : null;

        return [
            'firma' => $item['firma'] ?? null,
            'phone' => $phone,
            'phone_display' => $item['telefon_gosterim'] ?? null,
            'whatsapp' => $whatsapp,
            'email' => $item['email'] ?? null,
            'icerik' => $item['icerik'] ?? null,
            'profil_url' => $item['profil_url'] ?? null,
            'profil_resmi' => $item['profil_resmi'] ?? null,
            'tarih' => $item['tarih'] ?? null,
            'uyelik' => $item['uyelik'] ?? null,
            'uye_tipi' => $item['uye_tipi'] ?? null,
            'cevrimici' => ! empty($item['cevrimici']),
            'giris_gerekli' => ! empty($item['giris_gerekli']),
            'telefon_maskelenmis' => $item['telefon_maskelenmis'] ?? null,
            'raw_data' => $item,
        ];
    }
}
