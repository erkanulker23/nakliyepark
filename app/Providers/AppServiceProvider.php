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
        }
    }

    protected function shareSiteSettingsToViews(): void
    {
        $siteMetaTitle = \App\Models\Setting::get('site_meta_title', 'NakliyePark');
        $siteMetaDescription = \App\Models\Setting::get('site_meta_description', 'NakliyePark - Akıllı nakliye ve yük borsası');
        $siteMetaKeywords = \App\Models\Setting::get('site_meta_keywords', '');
        $siteLogoPath = \App\Models\Setting::get('site_logo', '');
        \Illuminate\Support\Facades\View::share([
            'site_meta_title' => $siteMetaTitle,
            'site_meta_description' => $siteMetaDescription,
            'site_meta_keywords' => $siteMetaKeywords,
            'site_logo_url' => $siteLogoPath ? asset('storage/' . $siteLogoPath) : null,
        ]);
    }

    protected function applyMailSettingsFromDatabase(): void
    {
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
