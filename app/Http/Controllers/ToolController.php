<?php

namespace App\Http\Controllers;

use App\Models\RoomTemplate;
use App\Models\Setting;

class ToolController extends Controller
{
    public function volume()
    {
        $rooms = RoomTemplate::orderBy('sort_order')->get();
        $metaTitle = Setting::get('tool_volume_meta_title') ?: 'Hacim Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_volume_meta_description') ?: 'Kayıtlı odalara göre taşınacak hacmi hesaplayın. Nakliye ihalesi için toplam m³ değerini kolayca bulun.';
        $toolContent = Setting::get('tool_volume_content', '');
        return view('tools.volume', compact('rooms', 'metaTitle', 'metaDescription', 'toolContent'));
    }

    public function distance()
    {
        $metaTitle = Setting::get('tool_distance_meta_title') ?: 'Mesafe Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_distance_meta_description') ?: 'Başlangıç ve varış ili seçerek nakliye mesafesini tahmini olarak hesaplayın. Harita üzerinde kuş uçuşu km görüntüleyin.';
        $toolContent = Setting::get('tool_distance_content', '');
        return view('tools.distance', compact('metaTitle', 'metaDescription', 'toolContent'));
    }

    public function cost()
    {
        $metaTitle = Setting::get('tool_cost_meta_title') ?: 'Tahmini Maliyet - NakliyePark';
        $metaDescription = Setting::get('tool_cost_meta_description') ?: 'Hacim ve mesafeye göre nakliyat maliyet aralığı hesaplayın. Kesin fiyat için ücretsiz ihale açıp teklif alın.';
        $toolContent = Setting::get('tool_cost_content', '');
        return view('tools.cost', compact('metaTitle', 'metaDescription', 'toolContent'));
    }
}
