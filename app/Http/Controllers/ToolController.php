<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class ToolController extends Controller
{
    public function volume()
    {
        $volumeRooms = config('volume_calculator.rooms');
        $volumeVehicles = config('volume_calculator.vehicles');
        $metaTitle = Setting::get('tool_volume_meta_title') ?: 'Hacim Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_volume_meta_description') ?: 'Odaya göre eşya seçerek taşınacak hacmi hesaplayın. Nakliye ihalesi için toplam m³ ve araç ihtiyacını görün.';
        $toolContent = Setting::get('tool_volume_content', '');
        $embedUrl = url(route('tools.volume.embed'));

        return view('tools.volume', compact('volumeRooms', 'volumeVehicles', 'metaTitle', 'metaDescription', 'toolContent', 'embedUrl'));
    }

    public function volumeEmbed()
    {
        $volumeRooms = config('volume_calculator.rooms');
        $volumeVehicles = config('volume_calculator.vehicles');

        return view('tools.volume-embed', compact('volumeRooms', 'volumeVehicles'));
    }

    public function distance()
    {
        $metaTitle = Setting::get('tool_distance_meta_title') ?: 'Mesafe Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_distance_meta_description') ?: 'Başlangıç ve varış ili seçerek nakliye mesafesini tahmini olarak hesaplayın. Harita üzerinde kuş uçuşu km görüntüleyin.';
        $toolContent = Setting::get('tool_distance_content', '');
        $embedUrl = url(route('tools.distance.embed'));

        return view('tools.distance', compact('metaTitle', 'metaDescription', 'toolContent', 'embedUrl'));
    }

    public function distanceEmbed()
    {
        return view('tools.distance-embed');
    }

    public function roadDistance()
    {
        $metaTitle = Setting::get('tool_road_distance_meta_title') ?: 'Karayolu Mesafe Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_road_distance_meta_description') ?: 'İl bazlı karayolu mesafesini tahmini hesaplayın. Nakliye planlaması için km bilgisi alın.';
        $toolContent = Setting::get('tool_road_distance_content', '');
        $embedUrl = url(route('tools.road-distance.embed'));

        return view('tools.road-distance', compact('metaTitle', 'metaDescription', 'toolContent', 'embedUrl'));
    }

    public function roadDistanceEmbed()
    {
        return view('tools.road-distance-embed');
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

    public function priceEstimator()
    {
        $metaTitle = Setting::get('tool_price_estimator_meta_title') ?: 'Tahmini Fiyat Hesaplama - NakliyePark';
        $metaDescription = Setting::get('tool_price_estimator_meta_description') ?: 'Km, eşya durumu ve kat bilgisine göre nakliye tahmini fiyatı hesaplayın. İhaleye benzer tüm bilgileri girin, anlık tahmin alın.';
        $toolContent = Setting::get('tool_price_estimator_content', '');
        $config = config('price_estimator');
        $embedUrl = url(route('tools.price-estimator.embed'));

        return view('tools.price-estimator', compact('metaTitle', 'metaDescription', 'toolContent', 'config', 'embedUrl'));
    }

    public function priceEstimatorEmbed()
    {
        $config = config('price_estimator');

        return view('tools.price-estimator-embed', compact('config'));
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
