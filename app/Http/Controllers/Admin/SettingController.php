<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'mail_mailer' => Setting::get('mail_mailer', config('mail.default', 'smtp')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => Setting::get('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_username' => Setting::get('mail_username'),
            'mail_password' => '',
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'commission_rate' => Setting::get('commission_rate', '10'),
            'site_meta_title' => Setting::get('site_meta_title', 'NakliyePark'),
            'site_meta_description' => Setting::get('site_meta_description', 'NakliyePark - Akıllı nakliye ve yük borsası'),
            'site_meta_keywords' => Setting::get('site_meta_keywords', ''),
            'site_logo' => Setting::get('site_logo', ''),
            'site_logo_dark' => Setting::get('site_logo_dark', ''),
            'site_favicon' => Setting::get('site_favicon', ''),
            'seo_google_verification' => Setting::get('seo_google_verification', config('seo.google_site_verification', '')),
            'seo_yandex_verification' => Setting::get('seo_yandex_verification', config('seo.yandex_verification', '')),
            'seo_bing_verification' => Setting::get('seo_bing_verification', config('seo.bing_verification', '')),
            'seo_head_codes' => Setting::get('seo_head_codes', ''),
            // Araç sayfaları (SEO + nasıl çalışır)
            'tool_volume_meta_title' => Setting::get('tool_volume_meta_title', ''),
            'tool_volume_meta_description' => Setting::get('tool_volume_meta_description', ''),
            'tool_volume_content' => Setting::get('tool_volume_content', ''),
            'tool_distance_meta_title' => Setting::get('tool_distance_meta_title', ''),
            'tool_distance_meta_description' => Setting::get('tool_distance_meta_description', ''),
            'tool_distance_content' => Setting::get('tool_distance_content', ''),
            'tool_road_distance_meta_title' => Setting::get('tool_road_distance_meta_title', ''),
            'tool_road_distance_meta_description' => Setting::get('tool_road_distance_meta_description', ''),
            'tool_road_distance_content' => Setting::get('tool_road_distance_content', ''),
            'tool_checklist_meta_title' => Setting::get('tool_checklist_meta_title', ''),
            'tool_checklist_meta_description' => Setting::get('tool_checklist_meta_description', ''),
            'tool_checklist_content' => Setting::get('tool_checklist_content', ''),
            'tool_moving_calendar_meta_title' => Setting::get('tool_moving_calendar_meta_title', ''),
            'tool_moving_calendar_meta_description' => Setting::get('tool_moving_calendar_meta_description', ''),
            'tool_moving_calendar_content' => Setting::get('tool_moving_calendar_content', ''),
            'custom_header_html' => Setting::get('custom_header_html', ''),
            'custom_footer_html' => Setting::get('custom_footer_html', ''),
            'custom_scripts' => Setting::get('custom_scripts', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_whatsapp' => Setting::get('contact_whatsapp', ''),
            'contact_hours' => Setting::get('contact_hours', ''),
            'show_firmalar_page' => Setting::get('show_firmalar_page', '1'),
            'openai_api_key_set' => ! empty(Setting::get('openai_api_key', '')),
            'openai_blog_model' => Setting::get('openai_blog_model', env('OPENAI_BLOG_MODEL', 'gpt-4o-mini')),
            'payment_enabled' => Setting::get('payment_enabled', '0'),
            'iyzico_api_key' => Setting::get('iyzico_api_key', ''),
            'iyzico_secret_key' => Setting::get('iyzico_secret_key', ''),
            'iyzico_sandbox' => Setting::get('iyzico_sandbox', '1'),
        ];
        $mailTemplateKeys = [
            'admin_new_ihale',
            'email_verification', 'musteri_welcome', 'nakliyeci_welcome',
            'musteri_ihale_created', 'musteri_ihale_published', 'musteri_teklif_received',
            'nakliyeci_ihale_preferred', 'nakliyeci_teklif_accepted', 'nakliyeci_contact_message',
            'password_reset',
        ];
        $mailDefaults = config('mail_templates_defaults', []);
        foreach ($mailTemplateKeys as $key) {
            $storedSubject = Setting::get('mail_tpl_' . $key . '_subject', '');
            $storedBody = Setting::get('mail_tpl_' . $key . '_body', '');
            $defaults = $mailDefaults[$key] ?? [];
            $settings['mail_tpl_' . $key . '_subject'] = $storedSubject !== '' ? (string) $storedSubject : (string) ($defaults['subject'] ?? '');
            // Gövde her zaman düz metin; view'da HTML olarak render edilmesin diye string zorunlu
            $settings['mail_tpl_' . $key . '_body'] = $storedBody !== '' ? (string) $storedBody : (string) ($defaults['body'] ?? '');
        }
        $paketlerJson = Setting::get('nakliyeci_paketler', '');
        $settings['nakliyeci_paketler'] = $paketlerJson ? (is_string($paketlerJson) ? json_decode($paketlerJson, true) : $paketlerJson) : config('nakliyepark.nakliyeci_paketler', []);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $section = $request->input('settings_section');

        $request->validate([
            'mail_mailer' => 'nullable|in:smtp,sendmail,log',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,null',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'site_meta_title' => 'nullable|string|max:255',
            'site_meta_description' => 'nullable|string|max:500',
            'site_meta_keywords' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:2048',
            'site_logo_dark' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:2048',
            'site_favicon' => 'nullable|file|max:1024',
            'seo_google_verification' => 'nullable|string|max:500',
            'seo_yandex_verification' => 'nullable|string|max:500',
            'seo_bing_verification' => 'nullable|string|max:500',
            'seo_head_codes' => 'nullable|string|max:15000',
            'custom_header_html' => 'nullable|string|max:10000',
            'custom_footer_html' => 'nullable|string|max:10000',
            'custom_scripts' => 'nullable|string|max:10000',
            'contact_phone' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email',
            'contact_address' => 'nullable|string|max:500',
            'contact_whatsapp' => 'nullable|string|max:50',
            'contact_hours' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:500',
            'openai_blog_model' => 'nullable|string|max:100',
            'payment_enabled' => 'nullable|in:0,1',
            'iyzico_api_key' => 'nullable|string|max:255',
            'iyzico_secret_key' => 'nullable|string|max:255',
            'iyzico_sandbox' => 'nullable|in:0,1',
        ]);

        // Sadece ilgili sekme alanlarını güncelle (tab sistemi)
        if ($section === 'mail' || $section === null) {
            $keys = ['mail_mailer', 'mail_from_name', 'mail_from_address', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption'];
            foreach ($keys as $key) {
                $value = $request->input($key);
                if ($key === 'mail_password' && $value === '') {
                    continue;
                }
                if ($value !== null && $value !== '') {
                    Setting::set($key, $value, 'mail');
                }
            }
        }

        if ($section === 'commission') {
            if ($request->has('commission_rate') && $request->commission_rate !== '') {
                Setting::set('commission_rate', $request->commission_rate, 'general');
            }
            return back()->with('success', 'Komisyon kaydedildi.');
        }

        if ($section === 'site' || $section === null) {
            Setting::set('show_firmalar_page', $request->boolean('show_firmalar_page') ? '1' : '0', 'general');
            foreach (['site_meta_title', 'site_meta_description', 'site_meta_keywords'] as $key) {
                Setting::set($key, $request->input($key) ?? '', 'seo');
            }
            foreach (['seo_google_verification', 'seo_yandex_verification', 'seo_bing_verification', 'seo_head_codes'] as $key) {
                Setting::set($key, $request->input($key) ?? '', 'seo');
            }
            if ($request->hasFile('site_logo')) {
                $oldPath = Setting::get('site_logo');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('site_logo')->store('site', 'public');
                Setting::set('site_logo', $path, 'general');
            }
            if ($request->hasFile('site_logo_dark')) {
                $oldPath = Setting::get('site_logo_dark');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('site_logo_dark')->store('site', 'public');
                Setting::set('site_logo_dark', $path, 'general');
            }
            if ($request->hasFile('site_favicon')) {
                $oldPath = Setting::get('site_favicon');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('site_favicon')->store('site', 'public');
                Setting::set('site_favicon', $path, 'general');
            }
        }

        if ($section === 'style' || $section === null) {
            Setting::set('custom_header_html', $request->input('custom_header_html') ?? '', 'style');
            Setting::set('custom_footer_html', $request->input('custom_footer_html') ?? '', 'style');
            Setting::set('custom_scripts', $request->input('custom_scripts') ?? '', 'style');
        }

        if ($section === 'contact') {
            Setting::set('contact_phone', $request->input('contact_phone') ?? '', 'contact');
            Setting::set('contact_email', $request->input('contact_email') ?? '', 'contact');
            Setting::set('contact_address', $request->input('contact_address') ?? '', 'contact');
            Setting::set('contact_whatsapp', $request->input('contact_whatsapp') ?? '', 'contact');
            Setting::set('contact_hours', $request->input('contact_hours') ?? '', 'contact');
            return back()->with('success', 'İletişim bilgileri kaydedildi.');
        }

        if ($section === 'api') {
            $apiKey = $request->input('openai_api_key');
            if ($apiKey !== null && $apiKey !== '') {
                Setting::set('openai_api_key', $apiKey, 'api');
            }
            Setting::set('openai_blog_model', $request->input('openai_blog_model') ?? env('OPENAI_BLOG_MODEL', 'gpt-4o-mini'), 'api');
            return back()->with('success', 'API ayarları kaydedildi.');
        }

        if ($section === 'payment') {
            Setting::set('payment_enabled', $request->boolean('payment_enabled') ? '1' : '0', 'payment');
            Setting::set('iyzico_api_key', $request->input('iyzico_api_key') ?? '', 'payment');
            $secret = $request->input('iyzico_secret_key');
            if ($secret !== null && $secret !== '') {
                Setting::set('iyzico_secret_key', $secret, 'payment');
            }
            Setting::set('iyzico_sandbox', $request->boolean('iyzico_sandbox') ? '1' : '0', 'payment');
            return back()->with('success', 'Ödeme ayarları kaydedildi.');
        }

        Log::channel('admin_actions')->info('Admin settings updated', [
            'admin_id' => auth()->id(),
            'section' => $section ?? 'multiple',
        ]);

        return back()->with('success', 'Ayarlar kaydedildi.');
    }

    public function updateMailTemplates(Request $request)
    {
        $keys = [
            'admin_new_ihale',
            'email_verification', 'musteri_welcome', 'nakliyeci_welcome',
            'musteri_ihale_created', 'musteri_ihale_published', 'musteri_teklif_received',
            'nakliyeci_ihale_preferred', 'nakliyeci_teklif_accepted', 'nakliyeci_contact_message',
            'password_reset',
        ];
        foreach ($keys as $key) {
            Setting::set('mail_tpl_' . $key . '_subject', $request->input('mail_tpl_' . $key . '_subject', ''), 'mail_templates');
            Setting::set('mail_tpl_' . $key . '_body', $request->input('mail_tpl_' . $key . '_body', ''), 'mail_templates');
        }
        return back()->with('success', 'Mail şablonları kaydedildi.');
    }

    public function updatePackages(Request $request)
    {
        $defaults = config('nakliyepark.nakliyeci_paketler', []);
        $packages = [];
        foreach ($defaults as $index => $def) {
            $id = $def['id'] ?? ('paket_' . $index);
            $packages[] = [
                'id' => $id,
                'name' => $request->input('paket_' . $id . '_name', $def['name'] ?? ''),
                'price' => (int) $request->input('paket_' . $id . '_price', $def['price'] ?? 0),
                'teklif_limit' => (int) $request->input('paket_' . $id . '_teklif_limit', $def['teklif_limit'] ?? 50),
                'description' => $request->input('paket_' . $id . '_description', $def['description'] ?? ''),
                'features' => $def['features'] ?? [],
                'cta' => $def['cta'] ?? 'Seç',
                'popular' => $def['popular'] ?? false,
            ];
        }
        Setting::set('nakliyeci_paketler', $packages, 'general');
        return back()->with('success', 'Paketler kaydedildi.');
    }

    public function updateToolPages(Request $request)
    {
        $keys = [
            'tool_volume_meta_title', 'tool_volume_meta_description', 'tool_volume_content',
            'tool_distance_meta_title', 'tool_distance_meta_description', 'tool_distance_content',
            'tool_road_distance_meta_title', 'tool_road_distance_meta_description', 'tool_road_distance_content',
            'tool_checklist_meta_title', 'tool_checklist_meta_description', 'tool_checklist_content',
            'tool_moving_calendar_meta_title', 'tool_moving_calendar_meta_description', 'tool_moving_calendar_content',
            'tool_price_estimator_meta_title', 'tool_price_estimator_meta_description', 'tool_price_estimator_content',
        ];
        $rules = [];
        foreach ($keys as $key) {
            $rules[$key] = 'nullable|string|max:' . (str_contains($key, 'content') ? '15000' : (str_contains($key, 'description') ? '500' : '255'));
        }
        $request->validate($rules);
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key) ?? '', 'tools');
        }
        return back()->with('success', 'Araç sayfaları içerikleri kaydedildi.');
    }

    public function sendTestMail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);
        $this->applyMailSettingsFromDatabase();
        $driver = config('mail.default');
        if ($driver === 'log' || $driver === 'array') {
            return back()->with('error', 'Test için SMTP kullanılmalı. Mail & Komisyon sekmesinde Mailer olarak "smtp" seçin ve SMTP bilgilerini kaydedin.');
        }
        $siteName = config('seo.site_name', 'NakliyePark');
        try {
            Mail::purge($driver);
            Mail::send('emails.test', [], function ($message) use ($request, $siteName) {
                $message->to($request->test_email)
                    ->subject($siteName . ' - Mail Testi')
                    ->priority(1);
            });
            return back()->with('success', 'Test e-postası gönderildi: ' . $request->test_email);
        } catch (\Throwable $e) {
            return back()->with('error', 'E-posta gönderilemedi. SMTP ayarlarınızı kontrol edin: ' . $e->getMessage());
        }
    }

    protected function applyMailSettingsFromDatabase(): void
    {
        $mailer = Setting::get('mail_mailer');
        if ($mailer) {
            config(['mail.default' => $mailer]);
        }
        $from = Setting::get('mail_from_address');
        if ($from) {
            config(['mail.from.address' => $from]);
            config(['mail.from.name' => Setting::get('mail_from_name', config('mail.from.name'))]);
        }
        $host = Setting::get('mail_host');
        if ($host) {
            config(['mail.mailers.smtp.host' => $host]);
            config(['mail.mailers.smtp.port' => Setting::get('mail_port', 587)]);
            config(['mail.mailers.smtp.username' => Setting::get('mail_username')]);
            config(['mail.mailers.smtp.password' => Setting::get('mail_password')]);
            config(['mail.mailers.smtp.encryption' => Setting::get('mail_encryption', 'tls')]);
        }
    }
}
