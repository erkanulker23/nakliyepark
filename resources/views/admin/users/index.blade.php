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
                <th>Firma / İhaleler</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="font-medium text-slate-800 dark:text-slate-200">{{ $u->name }}</td>
                    <td class="text-slate-600 dark:text-slate-400">{{ $u->email }}</td>
                    <td>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($u->role === 'admin') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300
                            @elseif($u->role === 'nakliyeci') bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300
                            @else bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 @endif">
                            {{ $u->role === 'nakliyeci' ? 'Nakliyeci' : ($u->role === 'musteri' ? 'Müşteri' : 'Admin') }}
                        </span>
                    </td>
                    <td>
                        @if($u->role === 'nakliyeci')
                            @if($u->company)
                                <a href="{{ route('admin.companies.edit', $u->company) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-medium">{{ $u->company->name }}</a>
                                @if(!$u->company->approved_at)
                                    <span class="text-amber-600 dark:text-amber-400 text-xs ml-1">(onay bekliyor)</span>
                                    <form method="POST" action="{{ route('admin.companies.approve', $u->company) }}" class="inline ml-1">
                                        @csrf
                                        <button type="submit" class="text-xs px-2 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600">Onayla</button>
                                    </form>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400 text-xs ml-1">(onaylı)</span>
                                @endif
                            @else
                                <span class="text-slate-500 dark:text-slate-400 text-sm">Firma oluşturmamış</span>
                            @endif
                        @else
                            <span class="text-slate-500 dark:text-slate-400">{{ $u->ihaleler_count ?? 0 }} ihale</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.users.edit', $u) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">Düzenle</a>
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
