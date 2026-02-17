<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Ihale;
use Illuminate\View\View;

class SitemapController extends Controller
{
    /**
     * Sitemap yönetim sayfası — URL, istatistikler, harita linki.
     */
    public function index(): View
    {
        $base = rtrim(config('app.url'), '/');
        $sitemapUrl = $base . '/sitemap.xml';

        $countStatic = 17;
        $countIhaleler = Ihale::query()->where('status', 'published')->whereNull('deleted_at')->count();
        $countCompanies = Company::query()->whereNotNull('approved_at')->whereNull('deleted_at')->count();
        $countBlog = BlogPost::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        $totalUrls = $countStatic
            + min($countIhaleler, 500)
            + min($countCompanies, 1000)
            + min($countBlog, 200);

        return view('admin.sitemap.index', [
            'sitemapUrl' => $sitemapUrl,
            'totalUrls' => $totalUrls,
            'countStatic' => $countStatic,
            'countIhaleler' => $countIhaleler,
            'countCompanies' => $countCompanies,
            'countBlog' => $countBlog,
        ]);
    }
}
