<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Ihale;
use App\Models\Teklif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeklifController extends Controller
{
    public function index(Request $request)
    {
        $query = Teklif::with(['ihale', 'company.user'])->latest();
        if (! $request->filled('date_from') && ! $request->filled('date_to') && ! $request->filled('status') && ! $request->filled('company_id') && ! $request->filled('ihale_id')) {
            $query->where('created_at', '>=', now()->subDays(30));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('ihale_id')) {
            $query->where('ihale_id', $request->ihale_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $teklifler = $query->paginate(20)->withQueryString();
        $companies = \App\Models\Company::orderBy('name')->get(['id', 'name']);
        $filters = $request->only(['status', 'company_id', 'ihale_id', 'date_from', 'date_to']);
        return view('admin.teklifler.index', compact('teklifler', 'companies', 'filters'));
    }

    public function edit(Teklif $teklif)
    {
        $teklif->load(['ihale', 'company']);
        return view('admin.teklifler.edit', compact('teklif'));
    }

    public function update(Request $request, Teklif $teklif)
    {
        if ($teklif->status === 'accepted') {
            return redirect()->route('admin.teklifler.edit', $teklif)->with('error', 'Kabul edilmiş teklif düzenlenemez (salt okunur). Ticari tutarlılık için yalnızca beklemedeki veya reddedilmiş teklifler düzenlenebilir.');
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,accepted,rejected',
        ]);
        $newStatus = $request->input('status');
        DB::transaction(function () use ($request, $teklif, $newStatus) {
            $teklif->update(array_merge($request->only(['amount', 'message', 'status']), [
                'pending_amount' => null,
                'pending_message' => null,
                'reject_reason' => null,
                'accepted_at' => $newStatus === 'accepted' ? now() : $teklif->accepted_at,
            ]));
            if ($newStatus === 'accepted') {
                Ihale::where('id', $teklif->ihale_id)->update(['status' => 'closed']);
                Teklif::where('ihale_id', $teklif->ihale_id)->where('id', '!=', $teklif->id)->update(['status' => 'rejected']);
            }
        });
        return redirect()->route('admin.teklifler.index')->with('success', 'Teklif güncellendi.');
    }

    /** Nakliyecinin talep ettiği güncellemeyi onayla (pending → amount/message) */
    public function approvePendingUpdate(Teklif $teklif)
    {
        if ($teklif->pending_amount === null) {
            return redirect()->route('admin.teklifler.edit', $teklif)->with('error', 'Bekleyen güncelleme yok.');
        }
        $teklif->update([
            'amount' => $teklif->pending_amount,
            'message' => $teklif->pending_message,
            'pending_amount' => null,
            'pending_message' => null,
        ]);
        return redirect()->route('admin.teklifler.edit', $teklif)->with('success', 'Güncelleme onaylandı.');
    }

    /** Nakliyecinin güncelleme talebini reddet (gerekçe nakliyeci tarafında görünsün) */
    public function rejectPendingUpdate(Request $request, Teklif $teklif)
    {
        $request->validate(['reject_reason' => 'nullable|string|max:1000']);
        $teklif->update([
            'pending_amount' => null,
            'pending_message' => null,
            'reject_reason' => $request->input('reject_reason'),
        ]);
        return redirect()->route('admin.teklifler.edit', $teklif)->with('success', 'Güncelleme talebi reddedildi.');
    }

    public function destroy(Request $request, Teklif $teklif)
    {
        $request->validate(['action_reason' => 'nullable|string|max:1000']);
        $before = $teklif->only(['id', 'ihale_id', 'company_id', 'amount', 'status', 'created_at']);
        $teklif->delete();
        AuditLog::adminAction('admin_teklif_deleted', Teklif::class, (int) $teklif->id, $before, ['deleted_at' => now()->toIso8601String()], $request->input('action_reason'));
        return redirect()->route('admin.teklifler.index')->with('success', 'Teklif silindi.');
    }
}
