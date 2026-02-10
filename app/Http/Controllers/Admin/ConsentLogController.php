<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsentLog;
use Illuminate\Http\Request;

class ConsentLogController extends Controller
{
    /**
     * KVKK açık rıza logları (hangi IP, hangi tarihte rıza verildi).
     */
    public function index(Request $request)
    {
        $query = ConsentLog::with(['user:id,name,email', 'ihale:id,from_city,to_city,slug'])
            ->latest('consented_at');

        if ($request->filled('consent_type')) {
            $query->where('consent_type', $request->consent_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('consented_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('consented_at', '<=', $request->date_to);
        }
        if ($request->filled('ip')) {
            $query->where('ip', 'like', '%' . $request->ip . '%');
        }

        $logs = $query->paginate(25)->withQueryString();
        $filters = $request->only(['consent_type', 'date_from', 'date_to', 'ip']);

        return view('admin.consent-logs.index', compact('logs', 'filters'));
    }
}
