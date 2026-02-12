<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use App\Models\User;
use App\Services\AdminNotifier;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'in:musteri,nakliyeci'],
        ], [
            'password.letters' => 'Şifre en az bir harf içermelidir.',
            'password.numbers' => 'Şifre en az bir rakam içermelidir.',
        ]);

        if (BlockedEmail::isBlocked($request->email)) {
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi engellenmiştir.'],
            ]);
        }

        if ($request->filled('phone') && BlockedPhone::isBlocked($request->phone)) {
            throw ValidationException::withMessages([
                'phone' => ['Bu telefon numarası engellenmiştir.'],
            ]);
        }

        if (BlockedIp::isBlocked($request->ip())) {
            throw ValidationException::withMessages([
                'email' => ['Erişim engellenmiştir.'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        \App\Services\SafeNotificationService::sendToUser($user, new \App\Notifications\WelcomeNotification($user->role), 'welcome_after_register');
        AdminNotifier::notify('user_registered', "Yeni kayıt: {$user->name} ({$user->email}) - Rol: {$user->role}", 'Yeni üye', ['url' => route('admin.users.edit', $user)]);
        Auth::login($user);

        if ($user->isNakliyeci()) {
            return redirect()->route('nakliyeci.company.create')->with('success', 'Firma bilgilerinizi tamamlayın.');
        }

        return redirect()->route('musteri.dashboard')->with('success', 'Hesabınız oluşturuldu.');
    }
}
