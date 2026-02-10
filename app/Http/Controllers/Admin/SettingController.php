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
            'tool_cost_meta_title' => Setting::get('tool_cost_meta_title', ''),
            'tool_cost_meta_description' => Setting::get('tool_cost_meta_description', ''),
            'tool_cost_content' => Setting::get('tool_cost_content', ''),
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
        ];
        $mailTemplateKeys = [
            'admin_new_ihale',
            'musteri_ihale_created', 'musteri_ihale_published', 'musteri_teklif_received',
            'nakliyeci_ihale_preferred', 'nakliyeci_teklif_accepted', 'nakliyeci_contact_message',
            'password_reset',
        ];
        foreach ($mailTemplateKeys as $key) {
            $settings['mail_tpl_' . $key . '_subject'] = Setting::get('mail_tpl_' . $key . '_subject', '');
            $settings['mail_tpl_' . $key . '_body'] = Setting::get('mail_tpl_' . $key . '_body', '');
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
            'tool_cost_meta_title', 'tool_cost_meta_description', 'tool_cost_content',
            'tool_checklist_meta_title', 'tool_checklist_meta_description', 'tool_checklist_content',
            'tool_moving_calendar_meta_title', 'tool_moving_calendar_meta_description', 'tool_moving_calendar_content',
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
        try {
            Mail::purge($driver);
            Mail::raw('NakliyePark mail ayarları testi. Bu e-postayı aldıysanız SMTP ayarlarınız çalışıyor.', function ($message) use ($request) {
                $message->to($request->test_email)->subject('NakliyePark - Mail Testi');
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
