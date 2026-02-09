<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomTemplate;
use Illuminate\Http\Request;

class RoomTemplateController extends Controller
{
    public function index()
    {
        $templates = RoomTemplate::orderBy('sort_order')->orderBy('id')->paginate(20);
        return view('admin.room-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.room-templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'default_volume_m3' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        RoomTemplate::create($data);
        return redirect()->route('admin.room-templates.index')->with('success', 'Oda şablonu eklendi.');
    }

    public function edit(RoomTemplate $roomTemplate)
    {
        return view('admin.room-templates.edit', compact('roomTemplate'));
    }

    public function update(Request $request, RoomTemplate $roomTemplate)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'default_volume_m3' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $roomTemplate->update($data);
        return redirect()->route('admin.room-templates.index')->with('success', 'Oda şablonu güncellendi.');
    }

    public function destroy(RoomTemplate $roomTemplate)
    {
        $roomTemplate->delete();
        return redirect()->route('admin.room-templates.index')->with('success', 'Oda şablonu silindi.');
    }
}
