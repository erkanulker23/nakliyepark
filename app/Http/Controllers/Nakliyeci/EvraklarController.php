<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvraklarController extends Controller
{
    public const TYPES = [
        'k1' => 'K1 Belgesi',
        'gb' => 'GB (Yeşil Kart)',
        'sigorta' => 'Sigorta',
        'ruhsat' => 'Ruhsat',
        'diger' => 'Diğer',
    ];

    public function index(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $documents = $company->documents()->orderBy('sort_order')->get();
        return view('nakliyeci.evraklar.index', compact('company', 'documents'));
    }

    public function create(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        return view('nakliyeci.evraklar.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')->with('error', 'Önce firma bilgilerinizi girin.');
        }
        $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(self::TYPES)),
            'title' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'expires_at' => 'nullable|date',
        ]);
        $path = $request->file('file')->store('company-documents/' . $company->id, 'public');
        $maxOrder = $company->documents()->max('sort_order') ?? 0;
        $company->documents()->create([
            'type' => $request->type,
            'title' => $request->title ?: self::TYPES[$request->type],
            'file_path' => $path,
            'expires_at' => $request->expires_at,
            'sort_order' => $maxOrder + 1,
        ]);
        return redirect()->route('nakliyeci.evraklar.index')->with('success', 'Evrak yüklendi.');
    }

    public function destroy(Request $request, $id)
    {
        $company = $request->user()->company;
        if (! $company) {
            abort(403);
        }
        $document = $company->documents()->findOrFail($id);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->route('nakliyeci.evraklar.index')->with('success', 'Evrak silindi.');
    }
}
