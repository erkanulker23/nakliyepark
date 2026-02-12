<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomepageEditorController extends Controller
{
    protected static array $sectionKeys = [
        'home_show_sponsors' => 'Sponsorlarımız',
        'home_show_how_it_works' => 'Nasıl çalışır?',
        'home_show_customer_experiences' => 'Müşteri Deneyimleri',
        'home_show_latest_ihaleler' => 'Son açılan ihaleler',
        'home_show_firmalar' => 'Nakliye firmaları',
        'home_show_defter' => 'Nakliyat defteri',
        'home_show_pricing' => 'Fiyat planları',
        'home_show_blog' => 'Bloglar',
    ];

    public static function getDefaultOrder(): array
    {
        return array_keys(self::$sectionKeys);
    }

    public function index()
    {
        $sections = [];
        foreach (array_keys(self::$sectionKeys) as $key) {
            $sections[$key] = Setting::get($key, '1') === '1';
        }
        $orderJson = Setting::get('home_section_order', '');
        $order = $orderJson ? (json_decode($orderJson, true) ?: self::getDefaultOrder()) : self::getDefaultOrder();
        $order = array_values(array_filter($order, fn ($k) => isset(self::$sectionKeys[$k])));
        $missing = array_diff(self::getDefaultOrder(), $order);
        if (! empty($missing)) {
            $order = array_merge($order, array_values($missing));
        }
        return view('admin.homepage-editor.index', [
            'sections' => $sections,
            'labels' => self::$sectionKeys,
            'order' => $order,
        ]);
    }

    public function update(Request $request)
    {
        foreach (array_keys(self::$sectionKeys) as $key) {
            Setting::set($key, $request->boolean($key) ? '1' : '0', 'homepage');
        }
        $order = $request->input('section_order', []);
        if (is_string($order)) {
            $order = array_filter(array_map('trim', explode(',', $order)));
        }
        $validOrder = array_values(array_intersect($order, array_keys(self::$sectionKeys)));
        $missing = array_diff(array_keys(self::$sectionKeys), $validOrder);
        if (! empty($missing)) {
            $validOrder = array_merge($validOrder, array_values($missing));
        }
        Setting::set('home_section_order', json_encode($validOrder), 'homepage');
        return back()->with('success', 'Anasayfa bölümleri ve sıralama kaydedildi.');
    }
}
