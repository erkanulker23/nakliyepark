<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class IhaleController extends Controller
{
    public function show(Request $request, Ihale $ihale)
    {
        if ($ihale->user_id !== $request->user()->id) {
            abort(403, 'Bu ihale size ait değil.');
        }
        $ihale->load(['teklifler.company.user', 'photos']);
        $acceptedTeklif = $ihale->acceptedTeklif();
        return view('musteri.ihaleler.show', compact('ihale', 'acceptedTeklif'));
    }

    public function acceptTeklif(Request $request, Ihale $ihale, Teklif $teklif)
    {
        if ($ihale->user_id !== $request->user()->id) {
            abort(403, 'Bu ihale size ait değil.');
        }
        if ($teklif->ihale_id !== $ihale->id) {
            abort(404);
        }
        if ($teklif->status === 'accepted') {
            return back()->with('info', 'Bu teklif zaten kabul edilmiş.');
        }
        \DB::transaction(function () use ($ihale, $teklif) {
            $ihale->teklifler()->where('id', '!=', $teklif->id)->update(['status' => 'rejected']);
            $teklif->update(['status' => 'accepted']);
            $ihale->update(['status' => 'closed']);
        });
        if ($teklif->company && $teklif->company->user) {
            UserNotification::notify(
                $teklif->company->user,
                'teklif_accepted',
                "{$ihale->from_city} → {$ihale->to_city} ihalesinde teklifiniz kabul edildi.",
                'Teklifiniz kabul edildi',
                ['url' => route('nakliyeci.teklifler.index')]
            );
        }
        return redirect()->route('musteri.ihaleler.show', $ihale)->with('success', 'Teklif kabul edildi. Firma ile iletişime geçebilirsiniz.');
    }
}
