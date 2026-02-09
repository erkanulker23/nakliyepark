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
        $firmalar = Company::whereNotNull('approved_at')->with('user')->latest()->take(6)->get();
        $defterIlanlari = YukIlani::with('company')->where('status', 'active')->latest()->take(6)->get();
        $sonBlog = BlogPost::whereNotNull('published_at')->orderByDesc('published_at')->take(3)->get();
        $sonIhale = Ihale::where('status', 'published')->latest()->first();
        $sonDefterKaydi = YukIlani::where('status', 'active')->latest()->first();
        $musteriVideolari = Review::whereNotNull('video_path')->with(['user', 'company'])->latest()->take(8)->get();
        return view('home', compact(
            'stats', 'sonIhaleler', 'firmalar', 'defterIlanlari', 'sonBlog',
            'sonIhale', 'sonDefterKaydi', 'musteriVideolari'
        ));
    }
}
