<?php

namespace App\Http\Controllers;

use App\Models\DefterReklami;
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
        $query = YukIlani::with('company')->where('status', 'active')->latest();

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

        $sonIhaleler = Ihale::where('status', 'published')
            ->latest()
            ->take(3)
            ->get(['id', 'from_city', 'to_city', 'move_date', 'volume_m3']);

        // Sayfa her yüklendiğinde rastgele reklamlar
        $reklamSidebar = DefterReklami::rastgeleKonumdan('sidebar', 5);
        $reklamUst = DefterReklami::rastgeleKonumdan('ust', 2);
        $reklamAlt = DefterReklami::rastgeleKonumdan('alt', 2);

        return view('defter.index', [
            'ilanlar' => $ilanlar,
            'sonIhaleler' => $sonIhaleler,
            'popularCities' => self::POPULAR_CITIES,
            'reklamSidebar' => $reklamSidebar,
            'reklamUst' => $reklamUst,
            'reklamAlt' => $reklamAlt,
        ]);
    }
}
