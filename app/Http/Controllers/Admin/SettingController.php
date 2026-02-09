<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
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
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
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
        ]);
        $keys = ['mail_from_name', 'mail_from_address', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption'];
        foreach ($keys as $key) {
            $value = $request->input($key);
            if ($key === 'mail_password' && $value === '') {
                continue;
            }
            if ($value !== null && $value !== '') {
                Setting::set($key, $value, 'mail');
            }
        }
        if ($request->has('commission_rate') && $request->commission_rate !== '') {
            Setting::set('commission_rate', $request->commission_rate, 'general');
        }
        // SEO
        foreach (['site_meta_title', 'site_meta_description', 'site_meta_keywords'] as $key) {
            $value = $request->input($key);
            Setting::set($key, $value ?? '', 'seo');
        }
        // Logo
        if ($request->hasFile('site_logo')) {
            $oldPath = Setting::get('site_logo');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('site_logo')->store('site', 'public');
            Setting::set('site_logo', $path, 'general');
        }
        return back()->with('success', 'Ayarlar kaydedildi.');
    }

    public function updateToolPages(Request $request)
    {
        $request->validate([
            'tool_volume_meta_title' => 'nullable|string|max:255',
            'tool_volume_meta_description' => 'nullable|string|max:500',
            'tool_volume_content' => 'nullable|string|max:15000',
            'tool_distance_meta_title' => 'nullable|string|max:255',
            'tool_distance_meta_description' => 'nullable|string|max:500',
            'tool_distance_content' => 'nullable|string|max:15000',
            'tool_cost_meta_title' => 'nullable|string|max:255',
            'tool_cost_meta_description' => 'nullable|string|max:500',
            'tool_cost_content' => 'nullable|string|max:15000',
        ]);
        $keys = [
            'tool_volume_meta_title', 'tool_volume_meta_description', 'tool_volume_content',
            'tool_distance_meta_title', 'tool_distance_meta_description', 'tool_distance_content',
            'tool_cost_meta_title', 'tool_cost_meta_description', 'tool_cost_content',
        ];
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key) ?? '', 'tools');
        }
        return back()->with('success', 'Araç sayfaları içerikleri kaydedildi.');
    }

    public function sendTestMail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);
        try {
            Mail::raw('NakliyePark mail ayarları testi. Bu e-postayı aldıysanız SMTP ayarlarınız çalışıyor.', function ($message) use ($request) {
                $message->to($request->test_email)->subject('NakliyePark - Mail Testi');
            });
            return back()->with('success', 'Test e-postası gönderildi: ' . $request->test_email);
        } catch (\Throwable $e) {
            return back()->with('error', 'Mail gönderilemedi: ' . $e->getMessage());
        }
    }
}
