@extends('layouts.app')

@section('title', 'Bildirimlerim - NakliyePark')

@section('content')
<div class="px-4 py-6 max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Bildirimlerim</h1>
        @php $unreadCount = auth()->user()->userNotifications()->whereNull('read_at')->count(); @endphp
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('musteri.notifications.mark-all-read') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">Tümünü okundu işaretle</button>
            </form>
        @endif
    </div>
    <nav class="mb-4">
        <a href="{{ route('musteri.dashboard') }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">← İhalelerime dön</a>
    </nav>
    <ul class="space-y-2">
        @forelse($notifications as $n)
            <li class="card-touch bg-white dark:bg-slate-800 p-4 {{ $n->read_at ? 'opacity-80' : '' }}">
                <div class="flex justify-between items-start gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-slate-800 dark:text-slate-100">{{ $n->title }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $n->message }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                        @if(!empty($n->data['url']))
                            <a href="{{ $n->data['url'] }}" class="inline-block mt-2 text-sm text-sky-600 dark:text-sky-400 font-medium">Görüntüle →</a>
                        @endif
                    </div>
                    @if(!$n->read_at)
                        <form method="POST" action="{{ route('musteri.notifications.read', $n->id) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="text-xs text-slate-500 hover:text-sky-600">Okundu</button>
                        </form>
                    @endif
                </div>
            </li>
        @empty
            <li class="py-12 text-center text-slate-500">Bildirim yok.</li>
        @endforelse
    </ul>
    @if($notifications->hasPages())
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
