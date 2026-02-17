<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use App\Models\IhalePhoto;
use App\Models\RoomTemplate;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\IhaleCreatedNotification;
use App\Notifications\NewIhaleAdminNotification;
use App\Models\ConsentLog;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class GuestWizardController extends Controller
{
    public function index(Request $request)
    {
        $rooms = RoomTemplate::orderBy('sort_order')->get();
        $step = 1;
        $forCompany = null;
        if ($request->filled('for_company')) {
            $forCompany = \App\Models\Company::whereNotNull('approved_at')->find($request->for_company);
        }
        $dataRetentionMonths = config('nakliyepark.data_retention_months', 24);
        return view('ihale.wizard', compact('rooms', 'step', 'forCompany', 'dataRetentionMonths'));
    }

    public function store(Request $request)
    {
        $serviceType = $request->input('service_type', 'evden_eve_nakliyat');
        $validServiceTypes = [
            Ihale::SERVICE_EVDEN_EVE,
            Ihale::SERVICE_SEHIRLERARASI,
            Ihale::SERVICE_PARCA_ESYA,
            Ihale::SERVICE_DEPOLAMA,
            Ihale::SERVICE_OFIS,
        ];
        if (! in_array($serviceType, $validServiceTypes, true)) {
            $serviceType = Ihale::SERVICE_EVDEN_EVE;
        }

        $rules = [
            'service_type' => 'nullable|string|in:'.implode(',', $validServiceTypes),
            'room_type' => 'nullable|string|max:100',
            'from_city' => 'required|string|max:100',
            'from_address' => 'nullable|string',
            'from_district' => 'nullable|string|max:100',
            'from_neighborhood' => 'nullable|string|max:150',
            'to_city' => $serviceType === Ihale::SERVICE_DEPOLAMA ? 'nullable|string|max:100' : 'required|string|max:100',
            'to_address' => 'nullable|string',
            'to_district' => 'nullable|string|max:100',
            'to_neighborhood' => 'nullable|string|max:150',
            'distance_km' => 'nullable|numeric|min:0',
            'move_date' => 'nullable|date',
            'move_date_end' => 'nullable|date',
            'date_preference' => 'nullable|string|in:tarih_araligi,fiyat_bakiyorum',
            'volume_m3' => $serviceType === Ihale::SERVICE_SEHIRLERARASI
                ? 'required|numeric|min:0'
                : 'nullable|numeric|min:0',
            'ev_salon' => 'nullable|string|max:1000',
            'ev_yatak_odasi' => 'nullable|string|max:1000',
            'ev_mutfak' => 'nullable|string|max:1000',
            'ev_diger' => 'nullable|string|max:1000',
            'ev_koli' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_items' => 'nullable|string',
            'guest_contact_name' => 'nullable|string|max:255',
            'guest_contact_email' => 'nullable|email',
            'guest_contact_phone' => 'nullable|string|max:20',
            'preferred_company_id' => 'nullable|exists:companies,id',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
            'kvkk_consent' => 'accepted', // KVKK açık rıza (misafir ve üye için kişisel veri işleme)
        ];
        if (! $request->user()) {
            $rules['guest_contact_name'] = 'required|string|max:255';
            $rules['guest_contact_email'] = 'required|email';
        }
        $validated = $request->validate($rules, [
            'kvkk_consent.accepted' => 'Kişisel verilerin işlenmesi için açık rıza vermeniz gerekmektedir.',
        ]);

        $validated['service_type'] = $serviceType;
        if ($request->filled('description_items')) {
            $validated['description'] = trim($request->input('description_items'))
                . ($request->filled('description') ? "\n".trim($request->input('description')) : '');
        }
        if ($serviceType === Ihale::SERVICE_EVDEN_EVE) {
            $evParts = [];
            if ($request->filled('ev_salon')) {
                $evParts[] = 'Salon: ' . trim($request->input('ev_salon'));
            }
            if ($request->filled('ev_yatak_odasi')) {
                $evParts[] = 'Yatak odası: ' . trim($request->input('ev_yatak_odasi'));
            }
            if ($request->filled('ev_mutfak')) {
                $evParts[] = 'Mutfak: ' . trim($request->input('ev_mutfak'));
            }
            if ($request->filled('ev_diger')) {
                $evParts[] = 'Diğer: ' . trim($request->input('ev_diger'));
            }
            if ($request->filled('ev_koli')) {
                $evParts[] = 'Koli: ' . trim($request->input('ev_koli'));
            }
            if (count($evParts) > 0) {
                $validated['description'] = implode("\n", $evParts) . ($request->filled('description') ? "\n" . trim($request->input('description')) : '');
            }
            $validated['volume_m3'] = $validated['volume_m3'] ?? 0;
        }
        if ($serviceType === Ihale::SERVICE_DEPOLAMA) {
            $validated['to_city'] = $validated['to_city'] ?? '';
            $validated['to_address'] = $validated['to_address'] ?? null;
        }
        $validated['volume_m3'] = $validated['volume_m3'] ?? 0;
        if ($request->input('date_preference') === 'fiyat_bakiyorum') {
            $validated['move_date'] = null;
            $validated['move_date_end'] = null;
            $fiyatNote = 'Fiyat karşılaştırması yapıyor, taşınma tarihi henüz belli değil.';
            $existing = $validated['description'] ?? '';
            $validated['description'] = $fiyatNote . ($existing ? "\n\n" . $existing : '');
        } else {
            $validated['move_date_end'] = $request->filled('move_date_end') ? $request->input('move_date_end') : null;
            if ($validated['move_date_end'] && $validated['move_date'] && $validated['move_date_end'] < $validated['move_date']) {
                $validated['move_date_end'] = $validated['move_date'];
            }
        }
        $validated['status'] = 'pending';
        if ($request->user()) {
            $validated['user_id'] = $request->user()->id;
            unset($validated['guest_contact_name'], $validated['guest_contact_email'], $validated['guest_contact_phone']);
        } else {
            $validated['user_id'] = null;
        }
        if ($request->filled('preferred_company_id')) {
            $validated['preferred_company_id'] = $request->input('preferred_company_id');
        } else {
            $validated['preferred_company_id'] = null;
        }
        unset($validated['photos'], $validated['description_items'], $validated['ev_salon'], $validated['ev_yatak_odasi'], $validated['ev_mutfak'], $validated['ev_diger'], $validated['ev_koli'], $validated['kvkk_consent']);

        try {
            $ihale = Ihale::create($validated);
        } catch (Throwable $e) {
            Log::error('İhale oluşturma hatası', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('ihale.create')->withInput()->with('error', 'İhale kaydedilirken bir hata oluştu. Lütfen tekrar deneyin. Sorun devam ederse bizimle iletişime geçin.');
        }

        // KVKK: Açık rıza logu (IP, tarih - admin panelinde görüntülenebilir)
        ConsentLog::log('kvkk_ihale', $request->user()?->id, $ihale->id, ['form' => 'ihale_wizard']);

        AdminNotifier::notify('ihale_created', "Yeni ihale: {$ihale->from_city} → {$ihale->to_city}" . ($ihale->user_id ? " (Üye)" : " (Misafir)"), 'Yeni ihale', ['url' => route('admin.ihaleler.show', $ihale)]);

        // Müşteriye panel bildirimi + e-posta: talebiniz alındı
        if ($ihale->user_id) {
            $ihale->load('user');
            UserNotification::notify(
                $ihale->user,
                'ihale_created',
                "İhale talebiniz alındı. İhaleniz onaydan sonra yayına girecek ve firmalardan teklif alabileceksiniz.",
                'İhale talebiniz alındı',
                ['url' => route('musteri.ihaleler.show', $ihale)]
            );
            \App\Services\SafeNotificationService::sendToUser($ihale->user, new IhaleCreatedNotification($ihale), 'ihale_created_musteri');
        } elseif ($ihale->guest_contact_email) {
            \App\Services\SafeNotificationService::sendToEmail($ihale->guest_contact_email, new IhaleCreatedNotification($ihale), 'ihale_created_guest');
        }

        // Admin kullanıcılarına e-posta: yeni ihale talebi
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Services\SafeNotificationService::sendToUser($admin, new NewIhaleAdminNotification($ihale), 'ihale_created_admin');
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('ihale-photos/' . $ihale->id, 'public');
                IhalePhoto::create(['ihale_id' => $ihale->id, 'path' => $path, 'sort_order' => $i]);
            }
        }

        if ($request->user()) {
            if ($request->user()->isMusteri()) {
                return redirect()->route('musteri.dashboard')->with('success', 'İhale talebiniz alındı. İhaleniz onaydan sonra yayına girecek ve firmalardan teklif alabileceksiniz.');
            }
            return redirect()->route('home')->with('success', 'İhale talebiniz alındı. Onaylandıktan sonra firmalar size dönüş yapacaktır.');
        }
        return redirect()->route('home')->with('success', 'Talebiniz alındı. Onaylandıktan sonra firmalar size e-posta veya telefondan dönüş yapacaktır.');
    }
}
