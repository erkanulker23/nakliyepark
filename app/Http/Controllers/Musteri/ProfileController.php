<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('musteri.bilgilerim.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB
            'remove_avatar' => ['nullable', 'boolean'],
        ];

        if ($request->filled('email') && $request->email !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
        }

        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules, [
            'current_password.current_password' => 'Mevcut şifreniz hatalı.',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['password'] ?? null)) {
            $user->password = $data['password'];
        }

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars/' . $user->id, 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('musteri.bilgilerim.edit')->with('success', 'Bilgileriniz güncellendi.');
    }

    /**
     * Giriş yapmış müşterinin e-postasına şifre sıfırlama linki gönderir.
     */
    public function sendPasswordResetLink(Request $request)
    {
        $user = $request->user();
        if (BlockedEmail::isBlocked($user->email)) {
            return redirect()->route('musteri.bilgilerim.edit')->with('error', 'Bu e-posta adresi engellenmiştir.');
        }
        $status = PasswordBroker::sendResetLink(['email' => $user->email]);
        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return redirect()->route('musteri.bilgilerim.edit')->with('success', 'Şifre sıfırlama linki e-posta adresinize gönderildi. Lütfen gelen kutunuzu kontrol edin.');
        }
        return redirect()->route('musteri.bilgilerim.edit')->with('error', 'Link gönderilemedi. Lütfen daha sonra tekrar deneyin veya destek ile iletişime geçin.');
    }
}
