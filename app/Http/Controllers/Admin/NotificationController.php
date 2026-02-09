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
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(string $id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Bildirim okundu işaretlendi.');
    }
}
