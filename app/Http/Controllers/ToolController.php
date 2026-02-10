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

    public function roadDistance()
    {
        $metaTitle = Setting::get('tool_road_distance_meta_title') ?: 'Karayolu Mesafe Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_road_distance_meta_description') ?: 'İl bazlı karayolu mesafesini tahmini hesaplayın. Nakliye planlaması için km bilgisi alın.';
        $toolContent = Setting::get('tool_road_distance_content', '');
        return view('tools.road-distance', compact('metaTitle', 'metaDescription', 'toolContent'));
    }

    public function checklist()
    {
        $metaTitle = Setting::get('tool_checklist_meta_title') ?: 'Taşınma Kontrol Listesi - NakliyePark';
        $metaDescription = Setting::get('tool_checklist_meta_description') ?: 'Taşınma öncesi yapılacaklar listesi. Adres değişikliği, abonelik iptali ve daha fazlası.';
        $toolContent = Setting::get('tool_checklist_content', '');
        $items = [
            '1 ay önce' => [
                'notify_landlord' => 'Ev sahibine veya kira sözleşmesine taşınma bildirimi',
                'find_new_home' => 'Yeni adres bulma veya taşınma tarihini netleştirme',
                'compare_companies' => 'Nakliye firmalarını karşılaştır, teklif al',
            ],
            '2 hafta önce' => [
                'change_address_post' => 'Posta yönlendirme (PTT) talebi',
                'cancel_subscriptions' => 'İnternet, TV, doğalgaz vb. abonelik iptali',
                'new_address_subscriptions' => 'Yeni adreste abonelik başlatma',
                'school_transfer' => 'Çocuk varsa okul nakil işlemleri',
            ],
            '1 hafta önce' => [
                'change_address_id' => 'Kimlik, ehliyet adres güncellemesi',
                'change_address_bank' => 'Banka ve kart adres güncellemesi',
                'change_address_insurance' => 'Sigorta poliçesi adres güncellemesi',
                'pack_start' => 'Nadiren kullanılan eşyaları paketlemeye başla',
            ],
            'Taşınma günü' => [
                'final_inspection' => 'Eski evde son kontrol, anahtarlar teslim',
                'meter_readings' => 'Sayaç okumaları kaydet',
                'confirm_movers' => 'Nakliye firması ile son koordinasyon',
            ],
        ];
        return view('tools.checklist', compact('metaTitle', 'metaDescription', 'toolContent', 'items'));
    }

    public function movingCalendar()
    {
        $metaTitle = Setting::get('tool_moving_calendar_meta_title') ?: 'Taşınma Takvimi - NakliyePark';
        $metaDescription = Setting::get('tool_moving_calendar_meta_description') ?: 'Taşınma tarihinize göre planlayıcı. Hangi işlemler ne zaman yapılmalı?';
        $toolContent = Setting::get('tool_moving_calendar_content', '');
        $phases = [
            '4 hafta önce' => [
                'Ev sahibine taşınma bildirimi',
                'Yeni ev ara / taşınma tarihini netleştir',
                'Nakliye firmalarından teklif al',
            ],
            '3 hafta önce' => [
                'Taşınacak firmayı seç, sözleşme imzala',
                'Taşınma tarihini firmayla teyit et',
            ],
            '2 hafta önce' => [
                'Posta yönlendirme talebi',
                'Abonelik iptali (internet, TV, doğalgaz)',
                'Yeni adreste abonelik başlatma',
            ],
            '1 hafta önce' => [
                'Kimlik ve ehliyet adres güncellemesi',
                'Banka ve sigorta adres güncellemesi',
                'Paketleme malzemeleri al, paketlemeye başla',
                'Değerli eşyaları özel paketle',
            ],
            '3 gün önce' => [
                'Buzdolabı ve derin dondurucuyu boşalt',
                'Nakliye firması ile son koordinasyon',
                'Yeni eve anahtar teslim tarihini teyit et',
            ],
            '1 gün önce' => [
                'Son banyo ve mutfak eşyalarını paketle',
                'Önemli belgeleri yanınıza alın',
                'Eski evde son temizlik planı',
            ],
            'Taşınma günü' => [
                'Sayaç okumalarını kaydet',
                'Eski evde son kontrol',
                'Anahtarları teslim et',
                'Yeni evde eşyaların yerleşimini kontrol et',
            ],
        ];
        $phaseOffsets = [
            '4 hafta önce' => -28,
            '3 hafta önce' => -21,
            '2 hafta önce' => -14,
            '1 hafta önce' => -7,
            '3 gün önce' => -3,
            '1 gün önce' => -1,
            'Taşınma günü' => 0,
        ];
        return view('tools.moving-calendar', compact('metaTitle', 'metaDescription', 'toolContent', 'phases', 'phaseOffsets'));
    }
}
