@extends('layouts.admin')

@section('title', 'Bildirimler')
@section('page_heading', 'Bildirimler')

@section('content')
<p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
    Toplam <strong>{{ $totalCount }}</strong> bildirim, <strong>{{ $unreadCount }}</strong> okunmamış.
</p>

<div class="flex flex-wrap items-center gap-3 mb-6">
    <form method="post" action="{{ route('admin.notifications.read-all') }}" class="inline">
        @csrf
        <button type="submit" class="admin-btn-secondary text-sm py-2 px-4" @if($unreadCount === 0) disabled @endif>Tümünü okundu işaretle</button>
    </form>
    <form method="post" action="{{ route('admin.notifications.destroy-all') }}" class="inline" onsubmit="return confirm('Tüm bildirimleri silmek istediğinize emin misiniz?');">
        @csrf
        <button type="submit" class="admin-btn-secondary text-sm py-2 px-4 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30" @if($totalCount === 0) disabled @endif>Tümünü sil</button>
    </form>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Tip</th>
                <th>Mesaj</th>
                <th class="text-right w-40">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $n)
                <tr class="{{ $n->read_at ? '' : 'bg-emerald-50/50 dark:bg-emerald-950/20' }}">
                    <td class="text-slate-600 dark:text-slate-400 text-sm whitespace-nowrap">{{ $n->created_at->format('d.m.Y H:i') }}</td>
                    <td><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">{{ $n->type }}</span></td>
                    <td>
                        @if($n->title)<strong class="text-slate-800 dark:text-slate-200">{{ $n->title }}</strong><br>@endif
                        <span class="text-slate-600 dark:text-slate-400">{{ $n->message }}</span>
                        @if(!empty($n->data['url']))<br><a href="{{ $n->data['url'] }}" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm">Görüntüle →</a>@endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2 flex-wrap">
                            @if(!$n->read_at)
                                <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-medium">Okundu</button>
                                </form>
                            @else
                                <span class="text-slate-400 text-sm">Okundu</span>
                            @endif
                            <form method="POST" action="{{ route('admin.notifications.destroy', $n->id) }}" class="inline" onsubmit="return confirm('Bu bildirimi silmek istiyor musunuz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm font-medium">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Bildirim yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($notifications->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
