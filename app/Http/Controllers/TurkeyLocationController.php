<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Türkiye il, ilçe ve mahalle verilerini api.turkiyeapi.dev üzerinden sağlar.
 * Tüm iller, ilçeler ve mahalleler bu API'den gelir.
 */
class TurkeyLocationController extends Controller
{
    private function baseUrl(): string
    {
        return config('services.turkiye_api.url', 'https://api.turkiyeapi.dev/v1');
    }

    public function provinces()
    {
        $response = Http::timeout(15)->get($this->baseUrl() . '/provinces');

        if (!$response->successful()) {
            return response()->json(['error' => 'İl listesi alınamadı.'], 502);
        }

        $body = $response->json();
        if (($body['status'] ?? '') !== 'OK' || empty($body['data'])) {
            return response()->json(['error' => 'Geçersiz veri.'], 502);
        }

        $provinces = collect($body['data'])->map(fn ($p) => [
            'id' => $p['id'],
            'name' => $p['name'],
            'latitude' => $p['coordinates']['latitude'] ?? null,
            'longitude' => $p['coordinates']['longitude'] ?? null,
        ])->values();

        return response()->json(['data' => $provinces]);
    }

    public function districts(Request $request)
    {
        $provinceId = $request->integer('province_id');
        if ($provinceId <= 0) {
            return response()->json(['error' => 'Geçerli il seçin.'], 400);
        }

        $response = Http::timeout(15)->get($this->baseUrl() . '/districts', [
            'provinceId' => $provinceId,
            'limit' => 1000,
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'İlçe listesi alınamadı.'], 502);
        }

        $body = $response->json();
        $data = [];
        if (($body['status'] ?? '') === 'OK' && !empty($body['data'])) {
            $data = collect($body['data'])->map(fn ($d) => [
                'id' => $d['id'],
                'name' => $d['name'],
                'province' => $d['province'] ?? null,
                'neighborhoods' => isset($d['neighborhoods']) ? array_map(fn ($n) => [
                    'id' => $n['id'],
                    'name' => $n['name'],
                ], $d['neighborhoods']) : [],
            ])->values()->all();
        }

        return response()->json(['data' => $data]);
    }
}
