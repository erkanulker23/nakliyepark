<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use App\Models\IhalePhoto;
use App\Models\RoomTemplate;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\IhalePublishedNotification;
use App\Notifications\NewIhaleAdminNotification;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WizardController extends Controller
{
    public function index(Request $request)
    {
        $step = (int) $request->get('step', 1);
        $rooms = RoomTemplate::orderBy('sort_order')->get();
        return view('wizard.index', compact('step', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_city' => 'required|string|max:100',
            'from_address' => 'nullable|string',
            'from_postal_code' => 'nullable|string|max:10',
            'to_city' => 'required|string|max:100',
            'to_address' => 'nullable|string',
            'to_postal_code' => 'nullable|string|max:10',
            'distance_km' => 'nullable|numeric|min:0',
            'move_date' => 'nullable|date',
            'volume_m3' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'published';
        unset($validated['photos']);

        $ihale = Ihale::create($validated);

        AdminNotifier::notify('ihale_created', "Yeni ihale (yayında): {$ihale->from_city} → {$ihale->to_city} (Üye)", 'Yeni ihale', ['url' => route('admin.ihaleler.show', $ihale)]);

        UserNotification::notify(
            $ihale->user,
            'ihale_created',
            'İhale talebiniz alındı. İhaleniz onaydan sonra yayına girecek ve firmalardan teklif alabileceksiniz.',
            'İhale talebiniz alındı',
            ['url' => route('musteri.ihaleler.show', $ihale)]
        );
        \App\Services\SafeNotificationService::sendToUser($ihale->user, new IhalePublishedNotification($ihale), 'wizard_ihale_published');

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Services\SafeNotificationService::sendToUser($admin, new NewIhaleAdminNotification($ihale), 'wizard_ihale_admin');
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('ihale-photos/' . $ihale->id, 'public');
                IhalePhoto::create([
                    'ihale_id' => $ihale->id,
                    'path' => $path,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('musteri.dashboard')->with('success', 'İhale talebiniz alındı. İhaleniz onaydan sonra yayına girecek ve firmalardan teklif alabileceksiniz.');
    }
}
