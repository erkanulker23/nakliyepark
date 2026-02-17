<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(30);
        $unreadCount = AdminNotification::whereNull('read_at')->count();
        $totalCount = AdminNotification::count();
        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'totalCount'));
    }

    public function markRead(string $id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Bildirim okundu işaretlendi.');
    }

    /** Tüm bildirimleri okundu işaretle */
    public function markAllRead()
    {
        AdminNotification::whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Tüm bildirimler okundu işaretlendi.');
    }

    /** Tek bildirim sil */
    public function destroy(string $id)
    {
        AdminNotification::findOrFail($id)->delete();
        return back()->with('success', 'Bildirim silindi.');
    }

    /** Tüm bildirimleri sil */
    public function destroyAll()
    {
        $count = AdminNotification::count();
        AdminNotification::query()->delete();
        return back()->with('success', $count > 0 ? "{$count} bildirim silindi." : 'Silinecek bildirim yok.');
    }
}
