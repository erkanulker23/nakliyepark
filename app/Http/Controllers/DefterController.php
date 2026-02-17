<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use App\Models\YukIlani;
use Illuminate\Http\Request;

class DefterController extends Controller
{
    /** Popüler şehirler (şehir chip filtreleri) */
    public const POPULAR_CITIES = [
        'İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Konya',
        'Adana', 'Mersin', 'Diyarbakır', 'Kocaeli', 'Manisa',
    ];

    public function index(Request $request)
    {
        $query = YukIlani::with(['company', 'yanitlar.company'])
            ->where('yuk_ilanlari.status', 'active')
            ->join('companies', 'companies.id', '=', 'yuk_ilanlari.company_id')
            ->orderByRaw("EXISTS (SELECT 1 FROM payment_requests pr WHERE pr.company_id = yuk_ilanlari.company_id AND pr.type = 'paket' AND pr.status = 'completed') DESC")
            ->orderByRaw('CASE WHEN companies.package = ? THEN 0 WHEN companies.package = ? THEN 1 WHEN companies.package = ? THEN 2 ELSE 3 END', ['kurumsal', 'profesyonel', 'baslangic'])
            ->latest('yuk_ilanlari.created_at')
            ->select('yuk_ilanlari.*');

        if ($request->filled('nereden')) {
            $query->where('from_city', 'like', '%' . $request->nereden . '%');
        }
        if ($request->filled('nereye')) {
            $query->where('to_city', 'like', '%' . $request->nereye . '%');
        }
        if ($request->filled('tarih')) {
            $query->whereDate('load_date', $request->tarih);
        }
        if ($request->filled('ara')) {
            $q = $request->ara;
            $query->where(function ($qry) use ($q) {
                $qry->where('from_city', 'like', "%{$q}%")
                    ->orWhere('to_city', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $ilanlar = $query->paginate(20)->withQueryString();

        $todayCount = YukIlani::where('status', 'active')->whereDate('created_at', today())->count();
        $weekCount = YukIlani::where('status', 'active')->where('created_at', '>=', now()->startOfWeek())->count();
        $totalCount = YukIlani::where('status', 'active')->count();

        $sonIhaleler = Ihale::where('status', 'published')
            ->latest()
            ->take(3)
            ->get(['id', 'slug', 'from_city', 'to_city', 'move_date', 'volume_m3']);

        return view('defter.index', [
            'ilanlar' => $ilanlar,
            'sonIhaleler' => $sonIhaleler,
            'popularCities' => self::POPULAR_CITIES,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount,
            'totalCount' => $totalCount,
        ]);
    }

    /** Tekil defter ilanı (WhatsApp paylaşım linki bu sayfaya gider) */
    public function show(YukIlani $yukIlani)
    {
        if ($yukIlani->status !== 'active') {
            abort(404);
        }
        $yukIlani->load(['company', 'yanitlar.company']);
        return view('defter.show', [
            'ilan' => $yukIlani,
            'popularCities' => self::POPULAR_CITIES,
        ]);
    }
}
