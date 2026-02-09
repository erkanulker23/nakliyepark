<?php

namespace App\Http\Controllers;

use App\Models\Company;

class FirmaController extends Controller
{
    public function index()
    {
        $query = Company::whereNotNull('approved_at')->with('user')->withCount('reviews');

        if ($q = request('q')) {
            $q = trim($q);
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%'.$q.'%')
                    ->orWhere('city', 'like', '%'.$q.'%')
                    ->orWhere('district', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            });
        }

        $firmalar = $query->latest()->paginate(12)->withQueryString();
        return view('firmalar.index', compact('firmalar'));
    }

    public function show(Company $company)
    {
        if (! $company->approved_at) {
            abort(404);
        }
        $company->load('user', 'reviews.user', 'contracts', 'vehicleImages', 'documents');
        $reviewAvg = round($company->reviews->avg('rating') ?? 0, 1);
        $reviewCount = $company->reviews->count();
        $completedJobsCount = $company->teklifler()->where('status', 'accepted')->count();
        return view('firmalar.show', compact('company', 'reviewAvg', 'reviewCount', 'completedJobsCount'));
    }
}
