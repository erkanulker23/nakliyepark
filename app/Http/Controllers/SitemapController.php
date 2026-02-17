<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Ihale;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * XML sitemap üretir (SEO — arama motorları için).
     */
    public function index(): Response
    {
        $base = rtrim(config('app.url'), '/');

        $urls = [];

        // Statik sayfalar — yüksek öncelik, günlük güncelleme
        $static = [
            ['path' => '', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['path' => '/ihaleler', 'priority' => '0.9', 'changefreq' => 'hourly'],
            ['path' => '/ihale/olustur', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/nakliye-firmalari', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['path' => '/nakliye-firmalari/haritadaki-nakliyeciler', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => '/defter', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => '/pazaryeri', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => '/iletisim', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => '/sss', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/kvkk-aydinlatma', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['path' => '/araclar/hacim', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/araclar/mesafe', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/araclar/tasinma-kontrol-listesi', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/araclar/tasinma-takvimi', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/araclar/tahmini-fiyat', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/firma-sorgula', 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($static as $s) {
            $urls[] = [
                'loc' => $base . $s['path'],
                'lastmod' => now()->toW3cString(),
                'changefreq' => $s['changefreq'],
                'priority' => $s['priority'],
            ];
        }

        // Yayındaki ihaleler (son 500)
        $ihaleler = Ihale::query()
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->limit(500)
            ->get(['id', 'slug', 'updated_at']);

        foreach ($ihaleler as $ihale) {
            $urls[] = [
                'loc' => $base . '/ihaleler/' . ($ihale->slug ?: (string) $ihale->id),
                'lastmod' => $ihale->updated_at?->toW3cString() ?? now()->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Onaylı firmalar
        $companies = Company::query()
            ->whereNotNull('approved_at')
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->limit(1000)
            ->get(['slug', 'updated_at']);

        foreach ($companies as $company) {
            if (empty($company->slug)) {
                continue;
            }
            $urls[] = [
                'loc' => $base . '/nakliye-firmalari/' . $company->slug,
                'lastmod' => $company->updated_at?->toW3cString() ?? now()->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        // Yayındaki blog yazıları
        $posts = BlogPost::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('updated_at')
            ->limit(200)
            ->get(['slug', 'updated_at']);

        foreach ($posts as $post) {
            if (empty($post->slug)) {
                continue;
            }
            $urls[] = [
                'loc' => $base . '/blog/' . $post->slug,
                'lastmod' => $post->updated_at?->toW3cString() ?? now()->toW3cString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = $this->buildXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function buildXml(array $urls): string
    {
        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $out .= '  <url>' . "\n";
            $out .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . '</loc>' . "\n";
            if (!empty($u['lastmod'])) {
                $out .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            if (!empty($u['changefreq'])) {
                $out .= '    <changefreq>' . $u['changefreq'] . '</changefreq>' . "\n";
            }
            if (isset($u['priority'])) {
                $out .= '    <priority>' . $u['priority'] . '</priority>' . "\n";
            }
            $out .= '  </url>' . "\n";
        }
        $out .= '</urlset>';
        return $out;
    }
}
