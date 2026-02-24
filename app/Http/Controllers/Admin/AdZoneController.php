<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdZone;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
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

        $adsense = [
            'adsense_code_snippet' => Setting::get('adsense_code_snippet', ''),
            'ads_txt_content' => Setting::get('ads_txt_content', ''),
            'adsense_meta_tag' => Setting::get('adsense_meta_tag', ''),
        ];

        return view('admin.reklam-alanlari.index', compact('reklamlar', 'adsense'));
    }

    public function updateAdsenseSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'adsense_code_snippet' => 'nullable|string|max:65535',
            'ads_txt_content' => 'nullable|string|max:65535',
            'adsense_meta_tag' => 'nullable|string|max:2000',
        ]);

        Setting::set('adsense_code_snippet', $request->input('adsense_code_snippet') ?? '', 'ads');
        Setting::set('ads_txt_content', $request->input('ads_txt_content') ?? '', 'ads');
        Setting::set('adsense_meta_tag', $request->input('adsense_meta_tag') ?? '', 'ads');

        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Google AdSense ayarları kaydedildi.');
    }

    public function create()
    {
        return view('admin.reklam-alanlari.create', [
            'sayfaSecenekleri' => AdZone::sayfaSecenekleri(),
            'konumSecenekleri' => AdZone::konumSecenekleri(),
        ]);
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

    public function edit(AdZone $reklam_alanlari)
    {
        return view('admin.reklam-alanlari.edit', [
            'reklam_alani' => $reklam_alanlari,
            'sayfaSecenekleri' => AdZone::sayfaSecenekleri(),
            'konumSecenekleri' => AdZone::konumSecenekleri(),
        ]);
    }

    public function update(Request $request, AdZone $reklam_alanlari)
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
        $reklam_alanlari->update($data);
        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Reklam alanı güncellendi.');
    }

    public function destroy(AdZone $reklam_alanlari)
    {
        $reklam_alanlari->delete();
        return redirect()->route('admin.reklam-alanlari.index')->with('success', 'Reklam alanı silindi.');
    }
}
