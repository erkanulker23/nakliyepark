<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MusteriController extends Controller
{
    public function index()
    {
        $musteriler = User::where('role', 'musteri')
            ->withCount('ihaleler')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.musteriler.index', compact('musteriler'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'musteri') {
            abort(404);
        }

        $user->load([
            'ihaleler' => fn ($q) => $q->latest()->with(['teklifler' => fn ($q) => $q->with('company.user')]),
        ]);

        return view('admin.musteriler.show', compact('user'));
    }
}
