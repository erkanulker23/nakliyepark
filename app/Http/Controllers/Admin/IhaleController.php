<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\IhalePreferredCompanyPublishedNotification;
use App\Notifications\IhalePublishedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IhaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Ihale::with('user')->latest();
        if (! $request->filled('date_from') && ! $request->filled('date_to') && ! $request->filled('q') && ! $request->filled('status') && ! $request->filled('from_city') && ! $request->filled('from_district') && ! $request->filled('to_city') && ! $request->filled('to_district') && ! $request->filled('service_type')) {
            $query->where('created_at', '>=', now()->subDays(30));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('from_city', 'like', '%' . $q . '%')
                    ->orWhere('to_city', 'like', '%' . $q . '%')
                    ->orWhere('from_address', 'like', '%' . $q . '%')
                    ->orWhere('to_address', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%');
                    })
                    ->orWhere('guest_contact_name', 'like', '%' . $q . '%')
                    ->orWhere('guest_contact_email', 'like', '%' . $q . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_city')) {
            $query->where('from_city', 'like', '%' . $request->from_city . '%');
        }
        if ($request->filled('from_district')) {
            $query->where('from_district', 'like', '%' . $request->from_district . '%');
        }
        if ($request->filled('to_city')) {
            $query->where('to_city', 'like', '%' . $request->to_city . '%');
        }
        if ($request->filled('to_district')) {
            $query->where('to_district', 'like', '%' . $request->to_district . '%');
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        $ihaleler = $query->paginate(20)->withQueryString();
        $filters = $request->only(['q', 'status', 'from_city', 'from_district', 'to_city', 'to_district', 'service_type', 'date_from', 'date_to']);
        return view('admin.ihaleler.index', compact('ihaleler', 'filters'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.ihaleler.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => $request->input('user_id') ?: null]);
        $data = $this->validateIhale($request);
        Ihale::create($data);
        return redirect()->route('admin.ihaleler.index')->with('success', 'İhale oluşturuldu.');
    }

    public function show(Ihale $ihale)
    {
        $ihale->load(['user', 'photos', 'teklifler.company.user']);
        return view('admin.ihaleler.show', compact('ihale'));
    }

    public function edit(Ihale $ihale)
    {
        $ihale->load('user');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.ihaleler.edit', compact('ihale', 'users'));
    }

    public function update(Request $request, Ihale $ihale)
    {
        $request->merge(['user_id' => $request->input('user_id') ?: null]);
        $data = $this->validateIhale($request, $ihale);
        $ihale->update($data);
        return redirect()->route('admin.ihaleler.show', $ihale)->with('success', 'İhale güncellendi.');
    }

    public function destroy(Request $request, Ihale $ihale)
    {
        $request->validate(['action_reason' => 'nullable|string|max:1000']);
        $before = $ihale->only(['id', 'user_id', 'status', 'from_city', 'to_city', 'created_at']);
        $ihale->delete();
        AuditLog::adminAction('admin_ihale_deleted', Ihale::class, (int) $ihale->id, $before, ['deleted_at' => now()->toIso8601String()], $request->input('action_reason'));
        Log::channel('admin_actions')->info('Admin ihale deleted', ['admin_id' => auth()->id(), 'ihale_id' => $ihale->id, 'guzergah' => $ihale->from_city . ' → ' . $ihale->to_city]);
        return redirect()->route('admin.ihaleler.index')->with('success', 'İhale silindi. Geri almak için destek ile iletişime geçin.');
    }

    public function updateStatus(Request $request, Ihale $ihale)
    {
        $request->validate(['status' => 'required|in:pending,draft,published,closed,cancelled']);
        $newStatus = $request->status;

        if ($newStatus === 'closed') {
            \DB::transaction(function () use ($ihale) {
                $ihale->update(['status' => 'closed']);
                $rejectedCount = Teklif::where('ihale_id', $ihale->id)->where('status', 'pending')->update(['status' => 'rejected']);
                AuditLog::adminAction(
                    'admin_ihale_closed_teklifler_rejected',
                    Ihale::class,
                    (int) $ihale->id,
                    ['before' => 'published', 'rejected_teklif_count' => $rejectedCount],
                    ['after' => 'closed'],
                    'İhale kapatıldı; kabul edilmemiş teklifler reddedildi.'
                );
            });
            Log::channel('admin_actions')->info('Admin ihale status updated (closed, teklifler rejected)', ['admin_id' => auth()->id(), 'ihale_id' => $ihale->id]);
        } else {
            $ihale->update(['status' => $newStatus]);
            Log::channel('admin_actions')->info('Admin ihale status updated', ['admin_id' => auth()->id(), 'ihale_id' => $ihale->id, 'status' => $newStatus]);
        }

        if ($newStatus === 'published') {
            \App\Services\AdminNotifier::notify('ihale_published', "İhale yayına alındı: {$ihale->from_city} → {$ihale->to_city} (#{$ihale->id})", 'İhale yayında', ['url' => route('admin.ihaleler.show', $ihale)]);
            if ($ihale->user_id) {
                $ihale->load('user');
                UserNotification::notify(
                    $ihale->user,
                    'ihale_published',
                    "{$ihale->from_city} → {$ihale->to_city} ihale talebiniz onaylandı ve yayına alındı. Firmalardan teklif almaya başlayabilirsiniz.",
                    'İhaleniz yayında',
                    ['url' => route('musteri.ihaleler.show', $ihale)]
                );
                \App\Services\SafeNotificationService::sendToUser($ihale->user, new IhalePublishedNotification($ihale), 'ihale_published_musteri');
            } elseif ($ihale->guest_contact_email) {
                \App\Services\SafeNotificationService::sendToEmail($ihale->guest_contact_email, new IhalePublishedNotification($ihale, true), 'ihale_published_guest');
            }
            if ($ihale->preferred_company_id) {
                $ihale->load('preferredCompany.user');
                if ($ihale->preferredCompany && $ihale->preferredCompany->user) {
                    UserNotification::notify(
                        $ihale->preferredCompany->user,
                        'ihale_preferred_published',
                        "Sizi tercih eden bir ihale yayına alındı: {$ihale->from_city} → {$ihale->to_city}. Hemen teklif verebilirsiniz.",
                        'Sizi tercih eden ihale yayında',
                        ['url' => route('nakliyeci.ihaleler.show', $ihale)]
                    );
                    \App\Services\SafeNotificationService::sendToUser($ihale->preferredCompany->user, new IhalePreferredCompanyPublishedNotification($ihale), 'ihale_preferred_published');
                }
            }
        } elseif ($newStatus === 'closed' || $newStatus === 'cancelled') {
            \App\Services\AdminNotifier::notify('ihale_status_changed', "İhale durumu değişti: {$ihale->from_city} → {$ihale->to_city} (#{$ihale->id}) → " . ($newStatus === 'closed' ? 'Kapatıldı' : 'İptal'), 'İhale durumu', ['url' => route('admin.ihaleler.show', $ihale)]);
        }
        $message = match ($newStatus) {
            'published' => 'İhale onaylandı ve yayına alındı.',
            'closed' => 'İhale kapatıldı. Kabul edilmemiş teklifler reddedildi.',
            default => 'İhale durumu güncellendi.',
        };
        return back()->with('success', $message);
    }

    /** Toplu yayınla: sadece pending ihaleler */
    public function bulkPublish(Request $request)
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_values(array_filter(array_map('intval', explode(',', $ids))));
            $request->merge(['ids' => $ids]);
        }
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:ihaleler,id']);
        $ihaleler = Ihale::whereIn('id', $request->ids)->where('status', 'pending')->get();
        $count = 0;
        foreach ($ihaleler as $ihale) {
            $ihale->update(['status' => 'published']);
            $count++;
            if ($ihale->user_id) {
                $ihale->load('user');
                UserNotification::notify($ihale->user, 'ihale_published', "{$ihale->from_city} → {$ihale->to_city} ihale talebiniz onaylandı.", 'İhaleniz yayında', ['url' => route('musteri.ihaleler.show', $ihale)]);
                \App\Services\SafeNotificationService::sendToUser($ihale->user, new IhalePublishedNotification($ihale), 'ihale_published_musteri');
            } elseif ($ihale->guest_contact_email) {
                \App\Services\SafeNotificationService::sendToEmail($ihale->guest_contact_email, new IhalePublishedNotification($ihale, true), 'ihale_published_guest');
            }
            if ($ihale->preferred_company_id) {
                $ihale->load('preferredCompany.user');
                if ($ihale->preferredCompany?->user) {
                    UserNotification::notify($ihale->preferredCompany->user, 'ihale_preferred_published', "Sizi tercih eden ihale yayında: {$ihale->from_city} → {$ihale->to_city}.", 'Sizi tercih eden ihale yayında', ['url' => route('nakliyeci.ihaleler.show', $ihale)]);
                    \App\Services\SafeNotificationService::sendToUser($ihale->preferredCompany->user, new IhalePreferredCompanyPublishedNotification($ihale), 'ihale_preferred_published');
                }
            }
        }
        return back()->with('success', "{$count} ihale yayına alındı.");
    }

    /** Toplu kapat: sadece published ihaleler; pending teklifler rejected yapılır */
    public function bulkClose(Request $request)
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_values(array_filter(array_map('intval', explode(',', $ids))));
            $request->merge(['ids' => $ids]);
        }
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:ihaleler,id']);
        $ihaleler = Ihale::whereIn('id', $request->ids)->where('status', 'published')->get();
        foreach ($ihaleler as $ihale) {
            \DB::transaction(function () use ($ihale) {
                $ihale->update(['status' => 'closed']);
                Teklif::where('ihale_id', $ihale->id)->where('status', 'pending')->update(['status' => 'rejected']);
            });
        }
        return back()->with('success', count($ihaleler) . ' ihale kapatıldı.');
    }

    /** Toplu sil */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_values(array_filter(array_map('intval', explode(',', $ids))));
            $request->merge(['ids' => $ids]);
        }
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:ihaleler,id']);
        $ihaleler = Ihale::whereIn('id', $request->ids)->get();
        $count = 0;
        foreach ($ihaleler as $ihale) {
            $before = $ihale->only(['id', 'user_id', 'status', 'from_city', 'to_city', 'created_at']);
            $ihale->delete();
            AuditLog::adminAction('admin_ihale_deleted', Ihale::class, (int) $ihale->id, $before, ['deleted_at' => now()->toIso8601String()], 'Toplu silme');
            Log::channel('admin_actions')->info('Admin ihale deleted (bulk)', ['admin_id' => auth()->id(), 'ihale_id' => $ihale->id]);
            $count++;
        }
        return back()->with('success', "{$count} ihale silindi.");
    }

    /** Nakliyeci teklifini iptal et (rejected) */
    public function rejectTeklif(Ihale $ihale, Teklif $teklif)
    {
        if ($teklif->ihale_id != $ihale->id) {
            abort(404);
        }
        $teklif->update(['status' => 'rejected', 'pending_amount' => null, 'pending_message' => null]);
        Log::channel('admin_actions')->info('Admin teklif rejected', [
            'admin_id' => auth()->id(),
            'ihale_id' => $ihale->id,
            'teklif_id' => $teklif->id,
            'company_id' => $teklif->company_id,
        ]);
        $companyName = $teklif->company ? $teklif->company->name : 'Firma';
        \App\Services\AdminNotifier::notify('teklif_rejected', "Teklif reddedildi: {$companyName} - {$ihale->from_city} → {$ihale->to_city} (#{$ihale->id})", 'Teklif reddedildi', ['url' => route('admin.ihaleler.show', $ihale)]);
        return redirect()->route('admin.ihaleler.show', $ihale)->with('success', 'Teklif iptal edildi.');
    }

    private function validateIhale(Request $request, ?Ihale $ihale = null): array
    {
        $validServiceTypes = [
            Ihale::SERVICE_EVDEN_EVE,
            Ihale::SERVICE_SEHIRLERARASI,
            Ihale::SERVICE_PARCA_ESYA,
            Ihale::SERVICE_DEPOLAMA,
            Ihale::SERVICE_OFIS,
        ];
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'service_type' => 'nullable|string|in:'.implode(',', $validServiceTypes),
            'room_type' => 'nullable|string|max:100',
            'guest_contact_name' => 'nullable|string|max:255',
            'guest_contact_email' => 'nullable|email',
            'guest_contact_phone' => 'nullable|string|max:20',
            'from_city' => 'required|string|max:100',
            'from_address' => 'nullable|string',
            'from_district' => 'nullable|string|max:100',
            'from_neighborhood' => 'nullable|string|max:150',
            'from_postal_code' => 'nullable|string|max:10',
            'to_city' => 'required|string|max:100',
            'to_address' => 'nullable|string',
            'to_district' => 'nullable|string|max:100',
            'to_neighborhood' => 'nullable|string|max:150',
            'to_postal_code' => 'nullable|string|max:10',
            'distance_km' => 'nullable|numeric|min:0',
            'move_date' => 'nullable|date',
            'move_date_end' => 'nullable|date|after_or_equal:move_date',
            'volume_m3' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,draft,published,closed,cancelled',
        ];
        return $request->validate($rules);
    }
}
