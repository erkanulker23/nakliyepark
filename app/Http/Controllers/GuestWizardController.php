<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use App\Models\IhalePhoto;
use App\Models\RoomTemplate;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

class GuestWizardController extends Controller
{
    public function index(Request $request)
    {
        $rooms = RoomTemplate::orderBy('sort_order')->get();
        $step = 1;
        return view('ihale.wizard', compact('rooms', 'step'));
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
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
        ];
        if (! $request->user()) {
            $rules['guest_contact_name'] = 'required|string|max:255';
            $rules['guest_contact_email'] = 'required|email';
        }
        $validated = $request->validate($rules);

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
        unset($validated['photos'], $validated['description_items'], $validated['ev_salon'], $validated['ev_yatak_odasi'], $validated['ev_mutfak'], $validated['ev_diger'], $validated['ev_koli']);

        $ihale = Ihale::create($validated);
        AdminNotifier::notify('ihale_created', "Yeni ihale: {$ihale->from_city} → {$ihale->to_city}" . ($ihale->user_id ? " (Üye)" : " (Misafir)"), 'Yeni ihale', ['url' => route('admin.ihaleler.show', $ihale)]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('ihale-photos/' . $ihale->id, 'public');
                IhalePhoto::create(['ihale_id' => $ihale->id, 'path' => $path, 'sort_order' => $i]);
            }
        }

        if ($request->user()) {
            if ($request->user()->isMusteri()) {
                return redirect()->route('musteri.dashboard')->with('success', 'İhale talebiniz alındı. Admin onayından sonra yayına girecek ve firmalardan teklif alabileceksiniz.');
            }
            return redirect()->route('home')->with('success', 'İhale talebiniz alındı. Onaylandıktan sonra firmalar size dönüş yapacaktır.');
        }
        return redirect()->route('home')->with('success', 'Talebiniz alındı. Onaylandıktan sonra firmalar size e-posta veya telefondan dönüş yapacaktır.');
    }
}
