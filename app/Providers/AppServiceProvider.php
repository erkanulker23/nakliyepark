<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $this->applyMailSettingsFromDatabase();
            $this->shareSiteSettingsToViews();
            $this->applyPackagesFromDatabase();
        }
    }

    protected function applyPackagesFromDatabase(): void
    {
        $stored = \App\Models\Setting::get('nakliyeci_paketler', '');
        if ($stored !== '') {
            $decoded = is_string($stored) ? json_decode($stored, true) : $stored;
            if (is_array($decoded) && count($decoded) > 0) {
                config(['nakliyepark.nakliyeci_paketler' => $decoded]);
            }
        }
    }

    protected function shareSiteSettingsToViews(): void
    {
        $siteMetaTitle = \App\Models\Setting::get('site_meta_title', 'NakliyePark');
        $siteMetaDescription = \App\Models\Setting::get('site_meta_description', 'NakliyePark - Akıllı nakliye ve yük borsası');
        $siteMetaKeywords = \App\Models\Setting::get('site_meta_keywords', '');
        $siteLogoPath = \App\Models\Setting::get('site_logo', '');
        $siteLogoDarkPath = \App\Models\Setting::get('site_logo_dark', '');
        $siteFaviconPath = \App\Models\Setting::get('site_favicon', '');
        $customHeader = \App\Models\Setting::get('custom_header_html', '');
        $customFooter = \App\Models\Setting::get('custom_footer_html', '');
        $customScripts = \App\Models\Setting::get('custom_scripts', '');
        $seoHeadCodes = \App\Models\Setting::get('seo_head_codes', '');
        // SEO doğrulama: admin panelden gelen değerler config'i override eder
        $googleVer = \App\Models\Setting::get('seo_google_verification', '');
        $yandexVer = \App\Models\Setting::get('seo_yandex_verification', '');
        $bingVer = \App\Models\Setting::get('seo_bing_verification', '');
        if ($googleVer !== '' || $yandexVer !== '' || $bingVer !== '') {
            config(['seo.google_site_verification' => $googleVer ?: config('seo.google_site_verification')]);
            config(['seo.yandex_verification' => $yandexVer ?: config('seo.yandex_verification')]);
            config(['seo.bing_verification' => $bingVer ?: config('seo.bing_verification')]);
        }
        \Illuminate\Support\Facades\View::share([
            'site_meta_title' => $siteMetaTitle,
            'site_meta_description' => $siteMetaDescription,
            'site_meta_keywords' => $siteMetaKeywords,
            'site_logo_url' => $siteLogoPath ? asset('storage/' . $siteLogoPath) : null,
            'site_logo_dark_url' => $siteLogoDarkPath ? asset('storage/' . $siteLogoDarkPath) : null,
            'site_favicon_url' => $siteFaviconPath ? asset('storage/' . $siteFaviconPath) : null,
            'custom_header_html' => $customHeader ?: null,
            'custom_footer_html' => $customFooter ?: null,
            'custom_scripts' => $customScripts ?: null,
            'seo_head_codes' => $seoHeadCodes ?: null,
        ]);
    }

    protected function applyMailSettingsFromDatabase(): void
    {
        $mailer = \App\Models\Setting::get('mail_mailer');
        if ($mailer) {
            config(['mail.default' => $mailer]);
        }
        $from = \App\Models\Setting::get('mail_from_address');
        if ($from) {
            config(['mail.from.address' => $from]);
            config(['mail.from.name' => \App\Models\Setting::get('mail_from_name', config('mail.from.name'))]);
        }
        $host = \App\Models\Setting::get('mail_host');
        if ($host) {
            config(['mail.mailers.smtp.host' => $host]);
            config(['mail.mailers.smtp.port' => \App\Models\Setting::get('mail_port', 587)]);
            config(['mail.mailers.smtp.username' => \App\Models\Setting::get('mail_username')]);
            config(['mail.mailers.smtp.password' => \App\Models\Setting::get('mail_password')]);
            config(['mail.mailers.smtp.encryption' => \App\Models\Setting::get('mail_encryption', 'tls')]);
        }
    }
}
