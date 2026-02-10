<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        ];

        if ($request->filled('email') && $request->email !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['password'] ?? null)) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('musteri.bilgilerim.edit')->with('success', 'Bilgileriniz güncellendi.');
    }
}
