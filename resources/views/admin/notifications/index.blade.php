@extends('layouts.admin')

@section('title', 'Bildirimler')
@section('page_heading', 'Bildirimler')

@section('content')
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Tip</th>
                <th>Mesaj</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $n)
                <tr class="{{ $n->read_at ? '' : 'bg-emerald-50/50' }}">
                    <td class="text-slate-600 text-sm whitespace-nowrap">{{ $n->created_at->format('d.m.Y H:i') }}</td>
                    <td><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">{{ $n->type }}</span></td>
                    <td>
                        @if($n->title)<strong>{{ $n->title }}</strong><br>@endif
                        {{ $n->message }}
                        @if(!empty($n->data['url']))<br><a href="{{ $n->data['url'] }}" class="text-emerald-600 hover:underline text-sm">Görüntüle →</a>@endif
                    </td>
                    <td class="text-right">
                        @if(!$n->read_at)
                            <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:underline text-sm font-medium">Okundu işaretle</button>
                            </form>
                        @else
                            <span class="text-slate-400 text-sm">Okundu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Bildirim yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($notifications->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
