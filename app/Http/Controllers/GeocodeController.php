<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Türkiye adresleri için koordinat döndürür (Nominatim / OSM).
 * İlçe veya mahalle seçildiğinde mesafe/fiyat araçlarında kullanılır.
 */
class GeocodeController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('q');
        if (! is_string($query) || trim($query) === '') {
            return response()->json(['lat' => null, 'lng' => null, 'error' => 'Sorgu gerekli (q=).']);
        }

        $query = trim($query);
        $cacheKey = 'geocode:' . md5($query);

        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return response()->json($cached);
        }

        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $query . ', Turkey',
            'format' => 'json',
            'limit' => 1,
            'countrycodes' => 'tr',
        ]);

        $response = Http::withHeaders([
            'User-Agent' => config('app.name', 'NakliyePark') . ' (nakliye mesafe aracı)',
        ])->timeout(10)->get($url);

        if (! $response->successful()) {
            return response()->json(['lat' => null, 'lng' => null, 'error' => 'Geocode isteği başarısız.']);
        }

        $data = $response->json();
        if (! is_array($data) || empty($data)) {
            $result = ['lat' => null, 'lng' => null];
            Cache::put($cacheKey, $result, now()->addDays(30));
            return response()->json($result);
        }

        $first = $data[0];
        $lat = isset($first['lat']) ? (float) $first['lat'] : null;
        $lng = isset($first['lon']) ? (float) $first['lon'] : null;
        $result = [
            'lat' => $lat,
            'lng' => $lng,
            'display_name' => $first['display_name'] ?? null,
        ];
        Cache::put($cacheKey, $result, now()->addDays(30));

        return response()->json($result);
    }
}
