<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showAdminLoginForm(Request $request)
    {
        $request->session()->put('url.intended', route('admin.dashboard'));

        return view('auth.login', ['admin_login' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (BlockedEmail::isBlocked($request->email)) {
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi engellenmiştir.'],
            ]);
        }

        if (BlockedIp::isBlocked($request->ip())) {
            throw ValidationException::withMessages([
                'email' => ['Erişim engellenmiştir.'],
            ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            Log::warning('Failed login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = Auth::user();

        // Normal giriş sayfasında admin hesapları kabul etme; bilgi vermeden hatalı giriş gibi göster
        if ($user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Girdiğiniz e-posta veya şifre hatalı.'],
            ]);
        }

        if ($user->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Hesabınız engellenmiştir.'],
            ]);
        }

        if ($user->phone && BlockedPhone::isBlocked($user->phone)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Hesabınız engellenmiştir.'],
            ]);
        }

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }
        if ($user->isNakliyeci()) {
            return redirect()->intended(route('nakliyeci.dashboard'));
        }

        return redirect()->intended(route('musteri.dashboard'));
    }

    /** Sadece yönetici hesabı ile giriş; /yonetici/admin formundan çağrılır */
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (BlockedEmail::isBlocked($request->email)) {
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi engellenmiştir.'],
            ]);
        }

        if (BlockedIp::isBlocked($request->ip())) {
            throw ValidationException::withMessages([
                'email' => ['Erişim engellenmiştir.'],
            ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            Log::warning('Admin login failed (wrong credentials)', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = Auth::user();

        if ($user->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Hesabınız engellenmiştir.'],
            ]);
        }

        if (! $user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => ['Bu sayfa sadece yöneticiler içindir. Müşteri veya nakliyeci girişi için ana giriş sayfasını kullanın.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
