<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DistanceCalculation;
use App\Models\PriceEstimatorCalculation;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $metaDescription = Setting::get('tool_distance_meta_description') ?: 'İl, ilçe ve isteğe bağlı mahalle seçerek nakliye karayolu mesafesini hesaplayın. Aynı il içinde ilçeler arası mesafe de hesaplanır.';
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

    /**
     * Firma sorgula: cep veya telefon numarasına göre onaylı firma listesi.
     * URL: /firma-sorgula?phone=532...
     */
    public function companyLookup(Request $request)
    {
        $metaTitle = Setting::get('tool_company_lookup_meta_title') ?: 'Firma Sorgula - NakliyePark';
        $metaDescription = Setting::get('tool_company_lookup_meta_description') ?: 'Nakliye firmasının cep veya sabit telefon numarasına göre firma sayfasını bulun.';
        $toolContent = Setting::get('tool_company_lookup_content', '');
        $companies = collect();
        $searchPhone = $request->input('phone') ?: $request->input('q');
        $searchPhone = is_string($searchPhone) ? trim($searchPhone) : '';

        if ($searchPhone !== '') {
            $normalized = Company::normalizePhoneForSearch($searchPhone);
            if ($normalized !== null) {
                $companies = Company::query()
                    ->whereNotNull('approved_at')
                    ->whereNull('blocked_at')
                    ->get()
                    ->filter(function (Company $company) use ($normalized) {
                        return Company::normalizePhoneForSearch($company->phone) === $normalized
                            || Company::normalizePhoneForSearch($company->phone_2) === $normalized
                            || Company::normalizePhoneForSearch($company->whatsapp) === $normalized;
                    })
                    ->values();
            }
        }

        return view('tools.company-lookup', compact('metaTitle', 'metaDescription', 'toolContent', 'companies', 'searchPhone'));
    }

    /** Son 10 mesafe hesaplaması (herkes için global). */
    public function distanceHistory(): JsonResponse
    {
        $items = DistanceCalculation::lastTen()->map(fn ($r) => [
            'id' => $r->id,
            'from' => $r->from_label,
            'to' => $r->to_label,
            'km' => $r->km,
            'route' => $r->route_label ?: $r->from_label . ' → ' . $r->to_label,
        ]);

        return response()->json(['data' => $items->values()->all()]);
    }

    /** Mesafe hesaplaması kaydet (global listeye eklenir). */
    public function storeDistanceHistory(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'km' => 'required|integer|min:0|max:10000',
            'route' => 'nullable|string|max:512',
        ]);
        DistanceCalculation::create([
            'from_label' => $request->from,
            'to_label' => $request->to,
            'km' => (int) $request->km,
            'route_label' => $request->route ?: $request->from . ' → ' . $request->to,
        ]);
        return response()->json(['ok' => true]);
    }

    /** Son 10 tahmini fiyat hesaplaması (herkes için global). */
    public function priceHistory(): JsonResponse
    {
        $items = PriceEstimatorCalculation::lastTen()->map(fn ($r) => [
            'id' => $r->id,
            'from' => $r->from_label,
            'to' => $r->to_label,
            'km' => $r->km,
            'price' => (float) $r->price,
            'room' => $r->room_label,
            'service_type' => $r->service_type,
            'route' => $r->route_label ?: ($r->from_label && $r->to_label ? $r->from_label . ' → ' . $r->to_label : ''),
        ]);

        return response()->json(['data' => $items->values()->all()]);
    }

    /** Tahmini fiyat hesaplaması kaydet (global listeye eklenir). */
    public function storePriceHistory(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
            'km' => 'required|integer|min:0|max:10000',
            'price' => 'required|numeric|min:0',
            'room' => 'nullable|string|max:255',
            'service_type' => 'nullable|string|max:64',
            'route' => 'nullable|string|max:512',
        ]);
        PriceEstimatorCalculation::create([
            'from_label' => $request->from,
            'to_label' => $request->to,
            'km' => (int) $request->km,
            'price' => $request->price,
            'room_label' => $request->room,
            'service_type' => $request->service_type,
            'route_label' => $request->route,
        ]);
        return response()->json(['ok' => true]);
    }
}
