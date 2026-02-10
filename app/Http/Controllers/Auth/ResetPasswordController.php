<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * E-postadaki linkle gelindiğinde: yeni şifre formu.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Yeni şifreyi kaydet.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ], [
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.letters' => 'Şifre en az bir harf içermelidir.',
            'password.numbers' => 'Şifre en az bir rakam içermelidir.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Şifreniz güncellendi. Yeni şifrenizle giriş yapabilirsiniz.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
