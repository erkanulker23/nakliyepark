@extends('layouts.admin')

@section('title', 'İletişim mesajları')
@section('page_heading', 'İletişim mesajları')

@section('content')
<p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Sitedeki iletişim formundan gelen mesajlar.</p>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Gönderen</th>
                <th>Konu</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $m)
                <tr class="{{ $m->read_at ? '' : 'bg-emerald-50/50 dark:bg-emerald-900/10' }}">
                    <td class="text-slate-600 dark:text-slate-400 text-sm whitespace-nowrap">{{ $m->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $m->name }}</span>
                        <br><a href="mailto:{{ $m->email }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">{{ $m->email }}</a>
                    </td>
                    <td>{{ $m->subject ?: '—' }}</td>
                    <td>
                        @if($m->read_at)
                            <span class="text-slate-500 text-sm">Okundu</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">Yeni</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.site-contact-messages.show', $m) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-medium">Görüntüle</a>
                        <form method="POST" action="{{ route('admin.site-contact-messages.destroy', $m) }}" class="inline ml-2" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Henüz iletişim mesajı yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($messages->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
