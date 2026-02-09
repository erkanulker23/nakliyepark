<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['ihaleler', 'reviews'])->with('company')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,musteri,nakliyeci',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($request->only(['name', 'email', 'role', 'phone']));
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }
}
