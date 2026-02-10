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
}
