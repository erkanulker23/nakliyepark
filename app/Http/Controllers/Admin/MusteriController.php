<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MusteriController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'musteri')->withCount('ihaleler');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%');
            });
        }

        $sort = $request->get('sort', 'name');
        $dir = $request->get('dir', 'asc') === 'desc' ? 'desc' : 'asc';
        if (in_array($sort, ['name', 'email', 'created_at'])) {
            $query->orderBy($sort, $dir);
        } else {
            $query->orderBy('name', 'asc');
        }

        $musteriler = $query->paginate(20)->withQueryString();
        $filters = $request->only(['q', 'sort', 'dir']);

        return view('admin.musteriler.index', compact('musteriler', 'filters'));
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

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
        ]);

        $deleted = User::where('role', 'musteri')->whereIn('id', $request->ids)->delete();
        $message = $deleted > 0
            ? $deleted . ' müşteri silindi.'
            : 'Seçilen kayıtlar müşteri olmadığı için silinemedi.';

        return redirect()->route('admin.musteriler.index')->with('success', $message);
    }
}
