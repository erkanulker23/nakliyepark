<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->userNotifications()
            ->latest()
            ->paginate(20);
        return view('musteri.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, string $id)
    {
        $notification = $request->user()->userNotifications()->findOrFail($id);
        $notification->markAsRead();
        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Bildirim okundu işaretlendi.');
    }

    public function markAllRead(Request $request)
    {
        $request->user()->userNotifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Tüm bildirimler okundu işaretlendi.');
    }
}
