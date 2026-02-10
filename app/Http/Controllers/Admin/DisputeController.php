<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispute::with(['ihale', 'company.user', 'openedByUser'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('ihale_id')) {
            $query->where('ihale_id', $request->ihale_id);
        }

        $disputes = $query->paginate(20)->withQueryString();
        $filters = $request->only(['status', 'ihale_id']);

        return view('admin.disputes.index', compact('disputes', 'filters'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['ihale', 'company.user', 'openedByUser', 'resolvedByUser']);
        return view('admin.disputes.show', compact('dispute'));
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:5000',
            'status' => 'required|in:resolved,admin_review',
        ]);
        $dispute->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'resolved_by_user_id' => $request->status === 'resolved' ? auth()->id() : null,
            'resolved_at' => $request->status === 'resolved' ? now() : null,
        ]);
        return redirect()->route('admin.disputes.show', $dispute)->with('success', 'Uyuşmazlık güncellendi.');
    }
}
