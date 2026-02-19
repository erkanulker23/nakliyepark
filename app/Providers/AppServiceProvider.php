<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // İhale route binding: slug veya id ile çözümle (degerlendir ve diğer /ihale/{ihale} rotaları)
        Route::bind('ihale', function (string $value) {
            if (is_numeric($value)) {
                return \App\Models\Ihale::findOrFail((int) $value);
            }
            return \App\Models\Ihale::where('slug', $value)->firstOrFail();
        });

        // Nakliye firmaları detay: slug ile bul, sadece onaylı ve engelli olmayan (forFirmalarShow scope)
        Route::bind('companyForShow', function (string $value) {
            return \App\Models\Company::forFirmalarShow()->where('slug', $value)->firstOrFail();
        });

        if (Schema::hasTable('settings')) {
            $this->applyMailSettingsFromDatabase();
            $this->applyOpenAiSettingsFromDatabase();
            $this->shareSiteSettingsToViews();
            $this->applyPackagesFromDatabase();
        }
        $this->composeHeaderNotifications();
    }

    /** Header bildirim dropdown: hem ana header hem nakliyeci/müşteri panelinde. Partial include edildiğinde veriyi doldurur. */
    protected function composeHeaderNotifications(): void
    {
        $inject = function ($view) {
            $view->with([
                'header_notifications' => collect(),
                'header_notifications_url' => null,
                'header_unread_count' => 0,
            ]);
            $user = Auth::user();
            if (! $user) {
                return;
            }
            if ($user->isAdmin()) {
                $view->with([
                    'header_notifications' => \App\Models\AdminNotification::latest()->take(25)->get(),
                    'header_notifications_url' => route('admin.notifications.index'),
                    'header_unread_count' => \App\Models\AdminNotification::whereNull('read_at')->count(),
                    'pending_companies_count' => \App\Models\Company::whereNull('approved_at')->count(),
                    'pending_ihaleler_count' => \App\Models\Ihale::where('status', 'pending')->count(),
                    'teklif_pending_count' => \App\Models\Teklif::whereNotNull('pending_amount')->count(),
                ]);
                return;
            }
            if ($user->isNakliyeci() || $user->isMusteri()) {
                $view->with([
                    'header_notifications' => $user->userNotifications()->latest()->take(25)->get(),
                    'header_notifications_url' => $user->isNakliyeci()
                        ? route('nakliyeci.notifications.index')
                        : route('musteri.notifications.index'),
                    'header_unread_count' => $user->userNotifications()->whereNull('read_at')->count(),
                ]);
            }
        };
        View::composer(['layouts.partials.header', 'layouts.partials.notifications-dropdown', 'layouts.admin'], $inject);
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
        $contactPhone = \App\Models\Setting::get('contact_phone', '');
        $contactEmail = \App\Models\Setting::get('contact_email', '');
        $contactAddress = \App\Models\Setting::get('contact_address', '');
        $contactWhatsapp = \App\Models\Setting::get('contact_whatsapp', '');
        $contactHours = \App\Models\Setting::get('contact_hours', '');
        $showFirmalarPage = \App\Models\Setting::get('show_firmalar_page', '1') === '1';
        $showPazaryeriPage = \App\Models\Setting::get('show_pazaryeri_page', '1') === '1';
        $showDefterPage = \App\Models\Setting::get('show_defter_page', '1') === '1';
        $showBlogPage = \App\Models\Setting::get('show_blog_page', '1') === '1';

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
            'contact_phone' => $contactPhone ?: null,
            'contact_email' => $contactEmail ?: null,
            'contact_address' => $contactAddress ?: null,
            'contact_whatsapp' => $contactWhatsapp ?: null,
            'contact_hours' => $contactHours ?: null,
            'show_firmalar_page' => $showFirmalarPage,
            'show_pazaryeri_page' => $showPazaryeriPage,
            'show_defter_page' => $showDefterPage,
            'show_blog_page' => $showBlogPage,
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

    protected function applyOpenAiSettingsFromDatabase(): void
    {
        $apiKey = \App\Models\Setting::get('openai_api_key', '');
        if ($apiKey !== '') {
            config(['openai.api_key' => $apiKey]);
        }
        $model = \App\Models\Setting::get('openai_blog_model', '');
        if ($model !== '') {
            config(['openai.blog_model' => $model]);
        }
    }
}
