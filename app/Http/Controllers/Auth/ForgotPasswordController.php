<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Şifremi unuttum formu (e-posta girişi).
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * E-posta ile şifre sıfırlama linki gönder.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (BlockedEmail::isBlocked($request->email)) {
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi engellenmiştir.'],
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
