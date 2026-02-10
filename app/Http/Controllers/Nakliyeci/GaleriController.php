<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $images = $company->vehicleImages()->orderBy('sort_order')->get();
        return view('nakliyeci.galeri.index', compact('company', 'images'));
    }

    public function create(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        return view('nakliyeci.galeri.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ], [
            'images.required' => 'En az bir fotoğraf seçin.',
            'images.*.image' => 'Seçilen dosyalar resim olmalıdır.',
            'images.*.max' => 'Her fotoğraf en fazla 5 MB olabilir.',
        ]);
        $caption = $request->filled('caption') ? $request->caption : null;
        $maxOrder = (int) $company->vehicleImages()->max('sort_order');
        $uploaded = 0;
        foreach ($request->file('images') as $file) {
            $path = $file->store('company-gallery/' . $company->id, 'public');
            $company->vehicleImages()->create([
                'path' => $path,
                'caption' => $caption,
                'sort_order' => ++$maxOrder,
            ]);
            $uploaded++;
        }
        $message = $uploaded === 1 ? 'Fotoğraf eklendi.' : "{$uploaded} fotoğraf eklendi.";
        return redirect()->route('nakliyeci.galeri.index')->with('success', $message);
    }

    public function destroy(Request $request, $id)
    {
        $company = $request->user()->company;
        if (! $company) {
            abort(403);
        }
        $image = $company->vehicleImages()->findOrFail($id);
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return redirect()->route('nakliyeci.galeri.index')->with('success', 'Fotoğraf silindi.');
    }
}
