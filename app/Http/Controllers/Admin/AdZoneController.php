<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdZone;
use Illuminate\Http\Request;

class AdZoneController extends Controller
{
    public function index(Request $request)
    {
        $query = AdZone::query()->orderBy('sayfa')->orderBy('konum')->orderBy('sira')->orderBy('id');

        if ($request->filled('sayfa')) {
            $query->where('sayfa', $request->sayfa);
        }
        if ($request->filled('konum')) {
            $query->where('konum', $request->konum);
        }
        if ($request->filled('tip')) {
            $query->where('tip', $request->tip);
        }

        $reklamlar = $query->paginate(20)->withQueryString();
        return view('admin.reklam-alanlari.index', compact('reklamlar'));
    }

    public function create()
    {
        return view('admin.reklam-alanlari.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sayfa'   => 'required|in:' . implode(',', array_keys(AdZone::sayfaSecenekleri())),
            'konum'   => 'required|in:' . implode(',', array_keys(AdZone::konumSecenekleri())),
            'baslik'  => 'nullable|string|max:255',
            'tip'     => 'required|in:code,image',
            'kod'     => 'nullable|string|max:65535',
            'resim'   => 'nullable|string|max:500',
            'link'    => 'nullable|string|max:500',
            'sira'    => 'nullable|integer|min:0',
            'aktif'   => 'boolean',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        $data['sira'] = (int) ($data['sira'] ?? 0);
        if ($data['tip'] === 'image') {
            $data['kod'] = null;
        } else {
            $data['resim'] = null;
            $data['link'] = null;
        }
        AdZone::create($data);
        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Reklam alanı eklendi.');
    }

    public function edit(AdZone $reklam_alani)
    {
        return view('admin.reklam-alanlari.edit', compact('reklam_alani'));
    }

    public function update(Request $request, AdZone $reklam_alani)
    {
        $data = $request->validate([
            'sayfa'   => 'required|in:' . implode(',', array_keys(AdZone::sayfaSecenekleri())),
            'konum'   => 'required|in:' . implode(',', array_keys(AdZone::konumSecenekleri())),
            'baslik'  => 'nullable|string|max:255',
            'tip'     => 'required|in:code,image',
            'kod'     => 'nullable|string|max:65535',
            'resim'   => 'nullable|string|max:500',
            'link'    => 'nullable|string|max:500',
            'sira'    => 'nullable|integer|min:0',
            'aktif'   => 'boolean',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        $data['sira'] = (int) ($data['sira'] ?? 0);
        if ($data['tip'] === 'image') {
            $data['kod'] = null;
        } else {
            $data['resim'] = null;
            $data['link'] = null;
        }
        $reklam_alani->update($data);
        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Reklam alanı güncellendi.');
    }

    public function destroy(AdZone $reklam_alani)
    {
        $reklam_alani->delete();
        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Reklam alanı silindi.');
    }
}
