<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\YukIlani;
use Illuminate\Http\Request;

class YukIlaniController extends Controller
{
    public function index(Request $request)
    {
        $query = YukIlani::with('company.user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('from_city', 'like', '%' . $q . '%')
                    ->orWhere('to_city', 'like', '%' . $q . '%')
                    ->orWhere('load_type', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhereHas('company', function ($c) use ($q) {
                        $c->where('name', 'like', '%' . $q . '%');
                    });
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_city')) {
            $query->where('from_city', 'like', '%' . $request->from_city . '%');
        }
        if ($request->filled('to_city')) {
            $query->where('to_city', 'like', '%' . $request->to_city . '%');
        }

        $ilanlar = $query->latest()->paginate(20)->withQueryString();
        $filters = $request->only(['q', 'status', 'from_city', 'to_city']);

        return view('admin.yuk-ilanlari.index', compact('ilanlar', 'filters'));
    }

    public function create()
    {
        $companies = Company::with('user')->whereNotNull('approved_at')->orderBy('name')->get();
        return view('admin.yuk-ilanlari.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'from_city' => 'required|string|max:100',
            'to_city' => 'required|string|max:100',
            'load_type' => 'nullable|string|max:100',
            'load_date' => 'nullable|date',
            'volume_m3' => 'nullable|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
        ]);
        YukIlani::create($data);
        return redirect()->route('admin.yuk-ilanlari.index')->with('success', 'Yük ilanı eklendi.');
    }

    public function show(YukIlani $yuk_ilanlari)
    {
        $yuk_ilanlari->load('company.user');
        return view('admin.yuk-ilanlari.show', compact('yuk_ilanlari'));
    }

    public function edit(YukIlani $yuk_ilanlari)
    {
        $yuk_ilanlari->load('company');
        $companies = Company::with('user')->whereNotNull('approved_at')->orderBy('name')->get();
        return view('admin.yuk-ilanlari.edit', compact('yuk_ilanlari', 'companies'));
    }

    public function update(Request $request, YukIlani $yuk_ilanlari)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'from_city' => 'required|string|max:100',
            'to_city' => 'required|string|max:100',
            'load_type' => 'nullable|string|max:100',
            'load_date' => 'nullable|date',
            'volume_m3' => 'nullable|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
        ]);
        $yuk_ilanlari->update($data);
        return redirect()->route('admin.yuk-ilanlari.index')->with('success', 'Yük ilanı güncellendi.');
    }

    public function destroy(YukIlani $yuk_ilanlari)
    {
        $yuk_ilanlari->delete();
        return redirect()->route('admin.yuk-ilanlari.index')->with('success', 'Yük ilanı silindi.');
    }
}
