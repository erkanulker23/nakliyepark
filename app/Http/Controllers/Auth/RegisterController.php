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
        // Form first_name + last_name ile geliyorsa name oluştur (JS kapalıyken de çalışsın)
        if (! $request->filled('name') && ($request->filled('first_name') || $request->filled('last_name'))) {
            $request->merge(['name' => trim($request->input('first_name', '') . ' ' . $request->input('last_name', ''))]);
        }
        if ($request->filled('phone')) {
            $digits = preg_replace('/\D/', '', $request->phone);
            if (strlen($digits) === 12 && str_starts_with($digits, '90')) {
                $digits = '0' . substr($digits, 2);
            } elseif (strlen($digits) === 10 && $digits[0] === '5') {
                $digits = '0' . $digits;
            }
            $digits = strlen($digits) > 11 ? substr($digits, 0, 11) : $digits;
            $request->merge(['phone' => $digits !== '' ? $digits : null]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'regex:/^0[0-9]{10}$/'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'in:musteri,nakliyeci'],
            'company_name' => ['required_if:role,nakliyeci', 'nullable', 'string', 'max:255'],
        ], [
            'password.letters' => 'Şifre en az bir harf içermelidir.',
            'password.numbers' => 'Şifre en az bir rakam içermelidir.',
            'phone.regex' => 'Telefon numarası +90 5XX XXX XX XX formatında olmalıdır.',
            'company_name.required_if' => 'Nakliyeci kaydı için firma adı gereklidir.',
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
        $adminTitle = $user->isNakliyeci() ? 'Yeni nakliyeci üye' : 'Yeni üye';
        AdminNotifier::notify('user_registered', "Yeni kayıt: {$user->name} ({$user->email}) - Rol: {$user->role}", $adminTitle, ['url' => route('admin.users.edit', $user)]);
        Auth::login($user);

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('success', 'Kaydınız başarıyla işleme alınmıştır. E-posta adresinize gönderilen link ile hesabınızı doğrulayın.');
        }

        if ($user->isNakliyeci()) {
            return redirect()->route('nakliyeci.company.create')
                ->with('success', 'Firma bilgilerinizi tamamlayın.')
                ->with('company_name', $request->input('company_name', ''));
        }

        return redirect()->route('musteri.dashboard')->with('success', 'Hesabınız oluşturuldu.');
    }
}
