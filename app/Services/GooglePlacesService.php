<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Google Places API (Legacy) ile işletme puan ve yorum sayısını çeker.
 * Veriler orijinal olarak Google'dan alınır.
 */
class GooglePlacesService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? (string) config('services.google.places_api_key', '');
    }

    public function hasApiKey(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Google Maps URL'sinden place_id çıkarır.
     * Desteklenen formatlar: ?place_id=ChIJ... veya /data=...!1sChIJ...
     */
    public function extractPlaceIdFromMapsUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        // Query string: ?place_id=ChIJ...
        $parsed = parse_url($url);
        if (! empty($parsed['query'])) {
            parse_str($parsed['query'], $params);
            if (! empty($params['place_id']) && $this->isValidPlaceId($params['place_id'])) {
                return $params['place_id'];
            }
        }

        // Path veya data parametresi içinde ChIJ... (Place ID formatı)
        if (preg_match('/!1s(ChIJ[a-zA-Z0-9_-]+)/', $url, $m)) {
            return $m[1];
        }
        if (preg_match('/place_id=(ChIJ[a-zA-Z0-9_-]+)/', $url, $m)) {
            return $m[1];
        }
        if (preg_match('/(ChIJ[a-zA-Z0-9_-]{20,})/', $url, $m)) {
            return $m[1];
        }

        // URL'den koordinat çıkar (@lat,lng) ve reverse geocode ile place_id al
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
            return $this->getPlaceIdFromCoordinates($lat, $lng);
        }

        return null;
    }

    protected function isValidPlaceId(string $id): bool
    {
        return str_starts_with($id, 'ChIJ') && strlen($id) >= 20;
    }

    /**
     * Koordinat ile Geocoding API'den place_id döner (ilk sonuç).
     */
    protected function getPlaceIdFromCoordinates(float $lat, float $lng): ?string
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$lat},{$lng}",
            'key' => $this->apiKey,
            'language' => 'tr',
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        $results = $data['results'] ?? [];
        foreach ($results as $result) {
            $placeId = $result['place_id'] ?? null;
            if ($placeId && $this->isValidPlaceId($placeId)) {
                return $placeId;
            }
        }

        return null;
    }

    /**
     * Place Details (Legacy) ile rating ve user_ratings_total döner.
     * @return array{rating: float, user_ratings_total: int}|null
     */
    public function fetchRatingAndReviewCount(string $googleMapsUrl): ?array
    {
        if (! $this->hasApiKey()) {
            Log::warning('Google Places API key not set');

            return null;
        }

        $placeId = $this->extractPlaceIdFromMapsUrl($googleMapsUrl);
        if (! $placeId) {
            Log::warning('Could not extract place_id from Google Maps URL', ['url' => $googleMapsUrl]);

            return null;
        }

        $response = Http::get($this->baseUrl . '/details/json', [
            'place_id' => $placeId,
            'fields' => 'rating,user_ratings_total',
            'key' => $this->apiKey,
            'language' => 'tr',
        ]);

        if (! $response->successful()) {
            Log::warning('Google Place Details request failed', ['status' => $response->status()]);

            return null;
        }

        $data = $response->json();
        if (($data['status'] ?? '') !== 'OK') {
            Log::warning('Google Place Details status not OK', ['status' => $data['status'] ?? 'unknown']);

            return null;
        }

        $result = $data['result'] ?? [];
        $rating = isset($result['rating']) ? (float) $result['rating'] : null;
        $total = isset($result['user_ratings_total']) ? (int) $result['user_ratings_total'] : null;

        if ($rating === null && $total === null) {
            return null;
        }

        return [
            'rating' => $rating,
            'user_ratings_total' => $total ?? 0,
        ];
    }
}
