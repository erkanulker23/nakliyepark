<?php

namespace App\Http\Controllers;

use App\Models\SiteContactMessage;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Adınızı girin.',
            'email.required' => 'E-posta adresinizi girin.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'message.required' => 'Mesajınızı yazın.',
        ]);

        $msg = SiteContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        AdminNotifier::notify(
            'site_contact_message',
            "Yeni iletişim formu: {$request->name} ({$request->email})" . ($request->subject ? " - Konu: {$request->subject}" : ''),
            'Yeni iletişim mesajı',
            ['url' => route('admin.site-contact-messages.show', $msg)]
        );

        return redirect()->route('contact.index')->with('success', 'Mesajınız alındı. En kısa sürede size dönüş yapacağız.');
    }
}
