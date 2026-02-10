<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MesajController extends Controller
{
    /**
     * Müşteriye gelen mesajlar (nakliyeci/firma tarafından gönderilen).
     * contact_messages: from_user_id = gönderen. Müşteri gelen mesajlar = ihale.user_id = auth id ve from_user_id != auth id.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $mesajlar = ContactMessage::query()
            ->whereHas('ihale', fn ($q) => $q->where('user_id', $user->id))
            ->where('from_user_id', '!=', $user->id)
            ->with(['ihale', 'teklif.company', 'fromUser', 'company'])
            ->latest()
            ->paginate(20);

        return view('musteri.mesajlar.index', compact('mesajlar'));
    }
}
