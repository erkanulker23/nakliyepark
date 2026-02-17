<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

/**
 * Türkiye il, ilçe ve mahalle verilerini api.turkiyeapi.dev üzerinden sağlar.
 * Tüm iller, ilçeler ve mahalleler bu API'den gelir.
 * API erişilemezse (DNS/network) 502 yerine boş data döner; frontend "İller yüklenemedi" gösterir.
 */
class TurkeyLocationController extends Controller
{
    private function baseUrl(): string
    {
        return config('services.turkiye_api.url', 'https://api.turkiyeapi.dev/v1');
    }

    public function provinces()
    {
        try {
            $response = Http::timeout(15)->get($this->baseUrl() . '/provinces');
        } catch (ConnectionException $e) {
            return response()->json(['data' => [], 'error' => 'İl listesi şu an yüklenemiyor. Lütfen daha sonra tekrar deneyin.']);
        }

        if (! $response->successful()) {
            return response()->json(['data' => [], 'error' => 'İl listesi alınamadı.']);
        }

        $body = $response->json();
        if (($body['status'] ?? '') !== 'OK' || empty($body['data'])) {
            return response()->json(['data' => [], 'error' => 'Geçersiz veri.']);
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
            return response()->json(['data' => [], 'error' => 'Geçerli il seçin.']);
        }

        try {
            $response = Http::timeout(15)->get($this->baseUrl() . '/districts', [
                'provinceId' => $provinceId,
                'limit' => 1000,
            ]);
        } catch (ConnectionException $e) {
            return response()->json(['data' => [], 'error' => 'İlçe listesi şu an yüklenemiyor.']);
        }

        if (! $response->successful()) {
            return response()->json(['data' => [], 'error' => 'İlçe listesi alınamadı.']);
        }

        $body = $response->json();
        $data = [];
        if (($body['status'] ?? '') === 'OK' && ! empty($body['data'])) {
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
