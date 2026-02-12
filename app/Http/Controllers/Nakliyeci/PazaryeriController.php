<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\PazaryeriListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PazaryeriController extends Controller
{
    private function getCompany(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return null;
        }
        return $company;
    }

    public function index(Request $request)
    {
        $company = $this->getCompany($request);
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $listings = $company->pazaryeriListings()->latest()->get();
        $vehicleTypes = PazaryeriListing::vehicleTypeLabels();
        $listingTypes = PazaryeriListing::listingTypeLabels();
        return view('nakliyeci.pazaryeri.index', compact('listings', 'vehicleTypes', 'listingTypes'));
    }

    public function create(Request $request)
    {
        $company = $this->getCompany($request);
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $vehicleTypes = PazaryeriListing::vehicleTypeLabels();
        $listingTypes = PazaryeriListing::listingTypeLabels();
        return view('nakliyeci.pazaryeri.create', compact('vehicleTypes', 'listingTypes'));
    }

    public function store(Request $request)
    {
        $company = $this->getCompany($request);
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $rules = [
            'title' => 'required|string|max:255',
            'vehicle_type' => 'required|string|in:' . implode(',', array_keys(PazaryeriListing::vehicleTypeLabels())),
            'listing_type' => 'required|string|in:sale,rent',
            'price' => 'nullable|numeric|min:0',
            'city' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:5000',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
        $request->validate($rules, [
            'title.required' => 'İlan başlığı gerekli.',
            'vehicle_type.required' => 'Araç tipi seçin.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('pazaryeri-listings/' . $company->id, 'public');
        }

        $extraImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $extraImages[] = $file->store('pazaryeri-listings/' . $company->id, 'public');
            }
        }

        $company->pazaryeriListings()->create([
            'title' => $request->title,
            'vehicle_type' => $request->vehicle_type,
            'listing_type' => $request->listing_type,
            'price' => $request->filled('price') ? $request->price : null,
            'city' => $request->city ?: null,
            'year' => $request->filled('year') ? (int) $request->year : null,
            'description' => $request->description ?: null,
            'image_path' => $imagePath,
            'images' => $extraImages ?: null,
            'status' => 'active',
        ]);

        return redirect()->route('nakliyeci.pazaryeri.index')->with('success', 'Pazaryeri ilanı eklendi.');
    }

    public function edit(Request $request, PazaryeriListing $pazaryeri)
    {
        $company = $this->getCompany($request);
        if (! $company) {
            abort(403);
        }
        if ($pazaryeri->company_id !== $company->id) {
            abort(404);
        }
        $vehicleTypes = PazaryeriListing::vehicleTypeLabels();
        $listingTypes = PazaryeriListing::listingTypeLabels();
        return view('nakliyeci.pazaryeri.edit', compact('pazaryeri', 'vehicleTypes', 'listingTypes'));
    }

    public function update(Request $request, PazaryeriListing $pazaryeri)
    {
        $company = $this->getCompany($request);
        if (! $company || $pazaryeri->company_id !== $company->id) {
            abort(403);
        }
        $rules = [
            'title' => 'required|string|max:255',
            'vehicle_type' => 'required|string|in:' . implode(',', array_keys(PazaryeriListing::vehicleTypeLabels())),
            'listing_type' => 'required|string|in:sale,rent',
            'price' => 'nullable|numeric|min:0',
            'city' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:5000',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
        $request->validate($rules);

        $imagePath = $pazaryeri->image_path;
        if ($request->hasFile('image_path')) {
            if ($pazaryeri->image_path) {
                Storage::disk('public')->delete($pazaryeri->image_path);
            }
            $imagePath = $request->file('image_path')->store('pazaryeri-listings/' . $company->id, 'public');
        }

        $extraImages = $pazaryeri->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $extraImages[] = $file->store('pazaryeri-listings/' . $company->id, 'public');
            }
        }

        $pazaryeri->update([
            'title' => $request->title,
            'vehicle_type' => $request->vehicle_type,
            'listing_type' => $request->listing_type,
            'price' => $request->filled('price') ? $request->price : null,
            'city' => $request->city ?: null,
            'year' => $request->filled('year') ? (int) $request->year : null,
            'description' => $request->description ?: null,
            'image_path' => $imagePath,
            'images' => $extraImages ?: null,
        ]);

        return redirect()->route('nakliyeci.pazaryeri.index')->with('success', 'İlan güncellendi.');
    }

    public function destroy(Request $request, PazaryeriListing $pazaryeri)
    {
        $company = $this->getCompany($request);
        if (! $company || $pazaryeri->company_id !== $company->id) {
            abort(403);
        }
        foreach ($pazaryeri->gallery_paths as $path) {
            Storage::disk('public')->delete($path);
        }
        $pazaryeri->delete();
        return redirect()->route('nakliyeci.pazaryeri.index')->with('success', 'İlan silindi.');
    }
}
