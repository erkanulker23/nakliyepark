<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefterReklami;
use Illuminate\Http\Request;

class DefterReklamiController extends Controller
{
    public function index()
    {
        $reklamlar = DefterReklami::orderBy('konum')->orderBy('sira')->orderBy('id')->paginate(20);
        return view('admin.defter-reklamlari.index', compact('reklamlar'));
    }

    public function create()
    {
        return view('admin.defter-reklamlari.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'baslik' => 'nullable|string|max:255',
            'icerik' => 'nullable|string',
            'resim' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:500',
            'konum' => 'required|in:sidebar,ust,alt',
            'aktif' => 'boolean',
            'sira' => 'nullable|integer|min:0',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        $data['sira'] = (int) ($data['sira'] ?? 0);
        DefterReklami::create($data);
        return redirect()->route('admin.defter-reklamlari.index')->with('success', 'Defter reklamı eklendi.');
    }

    public function edit(DefterReklami $defter_reklami)
    {
        return view('admin.defter-reklamlari.edit', compact('defter_reklami'));
    }

    public function update(Request $request, DefterReklami $defter_reklami)
    {
        $data = $request->validate([
            'baslik' => 'nullable|string|max:255',
            'icerik' => 'nullable|string',
            'resim' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:500',
            'konum' => 'required|in:sidebar,ust,alt',
            'aktif' => 'boolean',
            'sira' => 'nullable|integer|min:0',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        $data['sira'] = (int) ($data['sira'] ?? 0);
        $defter_reklami->update($data);
        return redirect()->route('admin.defter-reklamlari.index')->with('success', 'Defter reklamı güncellendi.');
    }

    public function destroy(DefterReklami $defter_reklami)
    {
        $defter_reklami->delete();
        return redirect()->route('admin.defter-reklamlari.index')->with('success', 'Defter reklamı silindi.');
    }
}
