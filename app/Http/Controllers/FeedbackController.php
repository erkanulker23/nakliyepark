<?php

namespace App\Http\Controllers;

use App\Models\ConsentLog;
use App\Models\SiteContactMessage;
use App\Services\AdminNotifier;
use App\Services\SpamGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FeedbackController extends Controller
{
    public const FEEDBACK_SUBJECT = 'Beta geri bildirimi';

    public function store(Request $request)
    {
        if (! SpamGuard::pass($request)) {
            throw ValidationException::withMessages([
                'message' => ['Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.'],
            ]);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:3000',
            'kvkk_consent' => 'accepted',
        ], [
            'name.required' => 'Adınızı girin.',
            'email.required' => 'E-posta adresinizi girin.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'message.required' => 'Geri bildiriminizi yazın.',
            'kvkk_consent.accepted' => 'Kişisel verilerin işlenmesi için açık rıza vermeniz gerekmektedir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors(), 'feedback')->withInput()->with('feedback_open', true);
        }

        $msg = SiteContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => self::FEEDBACK_SUBJECT,
            'message' => $request->message,
        ]);

        ConsentLog::log('kvkk_contact', null, null, ['site_contact_message_id' => $msg->id, 'source' => 'feedback']);

        AdminNotifier::notify(
            'site_contact_message',
            "Beta geri bildirimi: {$request->name} ({$request->email})",
            'Yeni geri bildirim',
            ['url' => route('admin.site-contact-messages.show', $msg)]
        );

        return redirect()->back()->with('success', 'Geri bildiriminiz alındı. Katkınız için teşekkür ederiz.');
    }
}
