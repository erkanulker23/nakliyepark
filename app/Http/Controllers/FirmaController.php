<?php

namespace App\Http\Controllers;

use App\Models\Company;

class FirmaController extends Controller
{
    public function index()
    {
        // Onaylı ve engelli olmayan tüm firmalar; paketli olanlar önce sıralanır
        $baseQuery = Company::whereNotNull('approved_at')->whereNull('blocked_at');

        $query = (clone $baseQuery)->with('user')->withCount('reviews');

        if ($q = request('q')) {
            $q = trim($q);
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%'.$q.'%')
                    ->orWhere('city', 'like', '%'.$q.'%')
                    ->orWhere('district', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            });
        }

        if (request()->filled('city')) {
            $query->where('city', 'like', '%' . request('city') . '%');
        }

        if (request()->filled('service')) {
            $service = request('service');
            $query->whereJsonContains('services', $service);
        }

        $firmalar = $query->orderByRaw('CASE WHEN package = ? THEN 0 WHEN package = ? THEN 1 WHEN package = ? THEN 2 ELSE 3 END', ['kurumsal', 'profesyonel', 'baslangic'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $cities = (clone $baseQuery)->distinct()->pluck('city')->filter()->sort()->values();
        $serviceLabels = Company::serviceLabels();
        $filters = request()->only(['q', 'city', 'service']);

        return view('firmalar.index', compact('firmalar', 'cities', 'serviceLabels', 'filters'));
    }

    public function show(Company $company)
    {
        if (! $company->approved_at || $company->blocked_at) {
            abort(404);
        }
        $company->load('user', 'reviews.user', 'contracts', 'vehicleImages', 'documents');
        $reviewAvg = round($company->reviews->avg('rating') ?? 0, 1);
        $reviewCount = $company->reviews->count();
        $completedJobsCount = $company->teklifler()->where('status', 'accepted')->count();
        $totalTeklifCount = $company->teklifler()->count();
        $acceptanceRate = $totalTeklifCount > 0 ? round(($completedJobsCount / $totalTeklifCount) * 100) : 0;
        return view('firmalar.show', compact('company', 'reviewAvg', 'reviewCount', 'completedJobsCount', 'totalTeklifCount', 'acceptanceRate'));
    }

    /** Haritadaki nakliyeciler: onaylı firmalar; canlı konum yoksa il merkezi koordinatı kullanılır */
    public function map()
    {
        $cityCoords = config('turkey_city_coordinates', []);
        $baseQuery = Company::whereNotNull('approved_at')->whereNull('blocked_at');

        $cityFilter = request('city', '');
        if ($cityFilter !== '') {
            $baseQuery->where('city', 'like', '%' . $cityFilter . '%');
        }

        $all = $baseQuery->orderBy('name')
            ->get(['id', 'slug', 'name', 'city', 'district', 'live_latitude', 'live_longitude', 'live_location_updated_at']);

        $withCoords = [];
        foreach ($all as $c) {
            $lat = null;
            $lng = null;
            if ($c->live_latitude !== null && $c->live_longitude !== null) {
                $lat = (float) $c->live_latitude;
                $lng = (float) $c->live_longitude;
            } elseif (! empty(trim((string) $c->city))) {
                $cityKey = trim($c->city);
                if (isset($cityCoords[$cityKey])) {
                    [$baseLat, $baseLng] = $cityCoords[$cityKey];
                    $lat = $baseLat + (mt_rand(-200, 200) / 10000.0);
                    $lng = $baseLng + (mt_rand(-200, 200) / 10000.0);
                }
            }
            if ($lat !== null && $lng !== null) {
                $withCoords[] = (object) [
                    'model' => $c,
                    'lat' => $lat,
                    'lng' => $lng,
                ];
            }
        }

        $firmalar = collect($withCoords)->map(fn ($e) => $e->model)->values();
        $cities = $all->pluck('city')->filter()->unique()->sort()->values();

        $companiesJson = collect($withCoords)->map(fn ($e) => [
            'id' => $e->model->id,
            'name' => $e->model->name,
            'city' => $e->model->city,
            'district' => $e->model->district,
            'lat' => $e->lat,
            'lng' => $e->lng,
            'url' => route('firmalar.show', $e->model),
        ])->values();

        return view('firmalar.map', [
            'firmalar' => $firmalar,
            'cities' => $cities,
            'companiesJson' => $companiesJson,
            'filters' => ['city' => $cityFilter],
        ]);
    }
}
