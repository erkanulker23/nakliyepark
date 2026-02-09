<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teklif;
use Illuminate\Http\Request;

class TeklifController extends Controller
{
    public function index(Request $request)
    {
        $query = Teklif::with(['ihale', 'company.user'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('ihale_id')) {
            $query->where('ihale_id', $request->ihale_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $teklifler = $query->paginate(20)->withQueryString();
        $companies = \App\Models\Company::orderBy('name')->get(['id', 'name']);
        $filters = $request->only(['status', 'company_id', 'ihale_id', 'date_from', 'date_to']);
        return view('admin.teklifler.index', compact('teklifler', 'companies', 'filters'));
    }

    public function edit(Teklif $teklif)
    {
        $teklif->load(['ihale', 'company']);
        return view('admin.teklifler.edit', compact('teklif'));
    }

    public function update(Request $request, Teklif $teklif)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,accepted,rejected',
        ]);
        $teklif->update($request->only(['amount', 'message', 'status']));
        return redirect()->route('admin.teklifler.index')->with('success', 'Teklif güncellendi.');
    }

    public function destroy(Teklif $teklif)
    {
        $teklif->delete();
        return redirect()->route('admin.teklifler.index')->with('success', 'Teklif silindi.');
    }
}
