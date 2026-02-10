<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Canlı konum güncelle (haritada görünsün açıkken tarayıcıdan gönderilir).
     * map_visible: true/false ile haritada görünürlüğü aç/kapat.
     */
    public function update(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return response()->json(['message' => 'Firma bulunamadı.'], 404);
        }

        $data = [];

        if ($request->has('map_visible')) {
            $data['map_visible'] = filter_var($request->input('map_visible'), FILTER_VALIDATE_BOOLEAN);
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');
        if (is_numeric($lat) && is_numeric($lng)) {
            $lat = (float) $lat;
            $lng = (float) $lng;
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                $data['live_latitude'] = $lat;
                $data['live_longitude'] = $lng;
                $data['live_location_updated_at'] = now();
            }
        }

        if (! empty($data)) {
            $company->update($data);
        }

        return response()->json([
            'ok' => true,
            'map_visible' => $company->map_visible,
            'live_location_updated_at' => $company->live_location_updated_at?->toIso8601String(),
        ]);
    }
}
