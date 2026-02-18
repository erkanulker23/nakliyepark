<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Faq;
use App\Models\Ihale;
use App\Models\Sponsor;
use App\Models\Review;
use App\Models\Setting;
use App\Models\YukIlani;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $homeSections = [
            'home_show_how_it_works' => Setting::get('home_show_how_it_works', '1') === '1',
            'home_show_customer_experiences' => Setting::get('home_show_customer_experiences', '1') === '1',
            'home_show_latest_ihaleler' => Setting::get('home_show_latest_ihaleler', '1') === '1',
            'home_show_firmalar' => Setting::get('home_show_firmalar', '1') === '1',
            'home_show_defter' => Setting::get('home_show_defter', '1') === '1',
            'home_show_sponsors' => Setting::get('home_show_sponsors', '1') === '1',
            'home_show_pricing' => Setting::get('home_show_pricing', '1') === '1',
            'home_show_blog' => Setting::get('home_show_blog', '1') === '1',
        ];

        $stats = [
            'ihale_count' => Ihale::where('status', 'published')->count(),
            'firma_count' => Company::whereNotNull('approved_at')->count(),
            'defter_count' => YukIlani::where('status', 'active')->count(),
        ];
        $sonIhaleler = Ihale::where('status', 'published')->withCount('teklifler')->latest()->take(6)->get();
        $firmalar = Company::whereNotNull('approved_at')->whereNull('blocked_at')->with('user')
            ->inRandomOrder()
            ->take(6)
            ->get();
        $firmalarHaritada = Company::visibleOnMap()->get(['id', 'slug', 'name', 'city', 'live_latitude', 'live_longitude', 'live_location_updated_at']);
        $defterIlanlari = YukIlani::with('company')->where('status', 'active')->latest()->take(20)->get();
        $sonBlog = BlogPost::whereNotNull('published_at')->orderByDesc('published_at')->take(3)->get();
        $sonIhale = Ihale::where('status', 'published')->latest()->first();
        $sonDefterKaydi = YukIlani::where('status', 'active')->latest()->first();
        $musteriVideolari = Review::whereNotNull('video_path')->with(['user', 'company'])->latest()->take(8)->get();
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        $faqsHomeMusteri = Faq::orderBy('sort_order')->orderBy('id')
            ->where('audience', Faq::AUDIENCE_MUSTERI)
            ->take(5)
            ->get();
        $faqsHomeNakliyeci = Faq::orderBy('sort_order')->orderBy('id')
            ->where('audience', Faq::AUDIENCE_NAKLIYECI)
            ->take(5)
            ->get();
        $sponsors = Sponsor::active()->ordered()->get();

        $orderJson = Setting::get('home_section_order', '');
        $defaultOrder = \App\Http\Controllers\Admin\HomepageEditorController::getDefaultOrder();
        $homeSectionOrder = $orderJson ? (json_decode($orderJson, true) ?: $defaultOrder) : $defaultOrder;
        $homeSectionOrder = array_values(array_filter($homeSectionOrder, fn ($k) => isset($homeSections[$k])));
        $missing = array_diff($defaultOrder, $homeSectionOrder);
        if (! empty($missing)) {
            $homeSectionOrder = array_merge($homeSectionOrder, array_values($missing));
        }

        $sectionViewMap = [
            'home_show_how_it_works' => 'how_it_works',
            'home_show_customer_experiences' => 'customer_experiences',
            'home_show_latest_ihaleler' => 'latest_ihaleler',
            'home_show_firmalar' => 'firmalar',
            'home_show_defter' => 'defter',
            'home_show_sponsors' => 'sponsors',
            'home_show_pricing' => 'pricing',
            'home_show_blog' => 'blog',
        ];

        return view('home', compact(
            'homeSections', 'homeSectionOrder', 'sectionViewMap', 'stats', 'sonIhaleler', 'firmalar', 'firmalarHaritada', 'defterIlanlari', 'sonBlog',
            'sonIhale', 'sonDefterKaydi', 'musteriVideolari', 'paketler', 'faqsHomeMusteri', 'faqsHomeNakliyeci', 'sponsors'
        ));
    }
}
