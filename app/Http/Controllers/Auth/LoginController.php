<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // CSRF token'ı yenile - form açıldığında fresh token olsun
        $request->session()->regenerateToken();
        
        return view('auth.login');
    }

    public function showAdminLoginForm(Request $request)
    {
        // Zaten admin olarak giriş yapmışsa panele yönlendir (guest middleware kullanılmadığı için burada kontrol)
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        $request->session()->put('url.intended', route('admin.dashboard'));
        // CSRF token'ı yenile - form açıldığında fresh token olsun
        $request->session()->regenerateToken();

        return view('auth.login', ['admin_login' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-posta adresi gerekli.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'password.required' => 'Şifre gerekli.',
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

        $userByEmail = User::where('email', $request->email)->first();
        if (! $userByEmail) {
            Log::warning('Failed login attempt (email not found)', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi ile kayıtlı hesap bulunamadı. Lütfen e-posta adresinizi kontrol edin veya kayıt olun.'],
            ]);
        }
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            Log::warning('Failed login attempt (wrong password)', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            throw ValidationException::withMessages([
                'password' => ['Girilen şifre hatalı. Şifrenizi kontrol edin veya şifremi unuttum ile sıfırlayın.'],
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

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            Log::warning('Admin login failed (unknown email)', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresi ile kayıtlı kullanıcı bulunamadı.'],
            ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            Log::warning('Admin login failed (wrong password)', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            throw ValidationException::withMessages([
                'password' => ['Şifre hatalı.'],
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
