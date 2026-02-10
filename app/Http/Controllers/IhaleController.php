<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IhaleController extends Controller
{
    public function index(Request $request)
    {
        App::setLocale('tr');
        $query = Ihale::where('status', 'published')->withCount('teklifler');

        if ($request->filled('from_city')) {
            $query->where('from_city', 'like', '%' . $request->from_city . '%');
        }
        if ($request->filled('to_city')) {
            $query->where('to_city', 'like', '%' . $request->to_city . '%');
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        if ($request->filled('move_date_from')) {
            $query->whereDate('move_date', '>=', $request->move_date_from);
        }
        if ($request->filled('move_date_to')) {
            $query->whereDate('move_date', '<=', $request->move_date_to);
        }
        if ($request->filled('volume_min')) {
            $query->where('volume_m3', '>=', (float) $request->volume_min);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'date_asc' => $query->orderBy('move_date', 'asc')->orderBy('created_at', 'desc'),
            'date_desc' => $query->orderBy('move_date', 'desc')->orderBy('created_at', 'desc'),
            'volume_desc' => $query->orderBy('volume_m3', 'desc')->orderBy('created_at', 'desc'),
            'teklif_desc' => $query->orderBy('teklifler_count', 'desc')->orderBy('created_at', 'desc'),
            default => $query->latest(),
        };

        $ihaleler = $query->paginate(12)->withQueryString();

        $filterOptions = [
            'cities_from' => Ihale::where('status', 'published')->distinct()->pluck('from_city')->filter()->sort()->values(),
            'cities_to'   => Ihale::where('status', 'published')->distinct()->pluck('to_city')->filter()->sort()->values(),
            'service_types' => Ihale::serviceTypeLabels(),
        ];

        $filters = $request->only(['from_city', 'to_city', 'service_type', 'move_date_from', 'move_date_to', 'volume_min', 'sort']);

        return view('ihaleler.index', compact('ihaleler', 'filterOptions', 'filters'));
    }

    public function show(Ihale $ihale)
    {
        if ($ihale->status !== 'published') {
            abort(404);
        }
        $ihale->load(['teklifler.company', 'photos', 'user']);
        $ihale->loadCount('teklifler');
        $nakliyeciVerdiMi = auth()->check()
            && auth()->user()->isNakliyeci()
            && auth()->user()->company
            && $ihale->teklifler->contains('company_id', auth()->user()->company->id);
        return view('ihaleler.show', compact('ihale', 'nakliyeciVerdiMi'));
    }

    public function create()
    {
        return view('ihale.create');
    }
}
