<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ihale;
use App\Models\Review;
use App\Models\YukIlani;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'ihale_count' => Ihale::where('status', 'published')->count(),
            'firma_count' => Company::whereNotNull('approved_at')->count(),
            'defter_count' => YukIlani::where('status', 'active')->count(),
        ];
        $sonIhaleler = Ihale::where('status', 'published')->withCount('teklifler')->latest()->take(6)->get();
        $firmalar = Company::whereNotNull('approved_at')->with('user')
            ->orderByRaw('CASE WHEN package = ? THEN 0 WHEN package = ? THEN 1 WHEN package = ? THEN 2 ELSE 3 END', ['kurumsal', 'profesyonel', 'baslangic'])
            ->latest()
            ->take(6)
            ->get();
        $firmalarHaritada = Company::visibleOnMap()->get(['id', 'slug', 'name', 'city', 'live_latitude', 'live_longitude', 'live_location_updated_at']);
        $defterIlanlari = YukIlani::with('company')->where('status', 'active')->latest()->take(6)->get();
        $sonBlog = BlogPost::whereNotNull('published_at')->orderByDesc('published_at')->take(3)->get();
        $sonIhale = Ihale::where('status', 'published')->latest()->first();
        $sonDefterKaydi = YukIlani::where('status', 'active')->latest()->first();
        $musteriVideolari = Review::whereNotNull('video_path')->with(['user', 'company'])->latest()->take(8)->get();
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        return view('home', compact(
            'stats', 'sonIhaleler', 'firmalar', 'firmalarHaritada', 'defterIlanlari', 'sonBlog',
            'sonIhale', 'sonDefterKaydi', 'musteriVideolari', 'paketler'
        ));
    }
}
