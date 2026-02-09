<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PazaryeriListing;
use Illuminate\Http\Request;

class PazaryeriController extends Controller
{
    public function index(Request $request)
    {
        $query = PazaryeriListing::with('company')
            ->where('status', 'active')
            ->latest();

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $listings = $query->paginate(12)->withQueryString();
        $vehicleTypes = PazaryeriListing::vehicleTypeLabels();
        $listingTypes = PazaryeriListing::listingTypeLabels();
        $cities = PazaryeriListing::where('status', 'active')->distinct()->pluck('city')->filter()->sort()->values();

        return view('pazaryeri.index', compact('listings', 'vehicleTypes', 'listingTypes', 'cities'));
    }

    public function show(PazaryeriListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }
        $listing->load('company');
        $vehicleTypes = PazaryeriListing::vehicleTypeLabels();
        $listingTypes = PazaryeriListing::listingTypeLabels();
        return view('pazaryeri.show', compact('listing', 'vehicleTypes', 'listingTypes'));
    }
}
