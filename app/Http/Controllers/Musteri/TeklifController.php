<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeklifController extends Controller
{
    /**
     * Müşterinin ihalelerine gelen tüm teklifleri listele (ihale bazlı gruplu veya düz liste).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $teklifler = $user->ihaleler()
            ->with(['teklifler' => fn ($q) => $q->with('company', 'ihale')->latest()])
            ->latest()
            ->get()
            ->pluck('teklifler')
            ->flatten()
            ->sortByDesc('created_at')
            ->values();

        $tekliflerPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $teklifler->forPage(request('page', 1), 15),
            $teklifler->count(),
            15,
            request('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('musteri.teklifler.index', [
            'teklifler' => $tekliflerPaginated,
        ]);
    }
}
