<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContactMessage;
use Illuminate\Http\Request;

class SiteContactMessageController extends Controller
{
    public function index()
    {
        $messages = SiteContactMessage::orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.site-contact-messages.index', compact('messages'));
    }

    public function show(SiteContactMessage $siteContactMessage)
    {
        if (! $siteContactMessage->read_at) {
            $siteContactMessage->update(['read_at' => now()]);
        }

        return view('admin.site-contact-messages.show', compact('siteContactMessage'));
    }

    public function destroy(SiteContactMessage $siteContactMessage)
    {
        $siteContactMessage->delete();

        return redirect()->route('admin.site-contact-messages.index')->with('success', 'Mesaj silindi.');
    }
}
