@extends('layouts.admin')

@section('title', 'Kullanıcılar')
@section('page_heading', 'Kullanıcılar')

@section('content')
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Ad</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>İhaleler</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="font-medium text-slate-800">{{ $u->name }}</td>
                    <td class="text-slate-600">{{ $u->email }}</td>
                    <td>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($u->role === 'admin') bg-indigo-100 text-indigo-800
                            @elseif($u->role === 'nakliyeci') bg-sky-100 text-sky-800
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="text-slate-500">{{ $u->ihaleler_count ?? 0 }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.users.edit', $u) }}" class="text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline ml-2" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Kullanıcı yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $users->links() }}</div>
    @endif
</div>
@endsection
