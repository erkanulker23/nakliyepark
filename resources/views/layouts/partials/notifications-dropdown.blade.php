{{-- Bildirim çanı + dropdown: hem nakliyeci hem müşteri panelinde ve ana header'da kullanılır. View Composer ile header_notifications doldurulur. --}}
@auth
@if($header_notifications_url ?? null)
<div class="relative" id="header-notifications-wrap">
    <button type="button" class="header-notifications-btn btn-ghost rounded-lg p-2.5 relative {{ ($header_unread_count ?? 0) > 0 ? 'notification-bell-unread' : '' }}" aria-label="Bildirimler" aria-expanded="false" aria-haspopup="true" aria-controls="header-notifications-panel" id="header-notifications-btn" title="Bildirimler">
        <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if(($header_unread_count ?? 0) > 0)<span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-amber-500 text-white text-xs font-semibold ring-2 ring-white dark:ring-zinc-900">{{ ($header_unread_count ?? 0) > 99 ? '99+' : $header_unread_count }}</span>@endif
    </button>
    <div id="header-notifications-panel" class="absolute right-0 top-full mt-1 w-80 max-h-[min(80vh,400px)] overflow-y-auto rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg z-[100] py-1 hidden" role="menu" aria-labelledby="header-notifications-btn">
        <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800 sticky top-0 bg-white dark:bg-zinc-900">
            <span class="font-semibold text-zinc-900 dark:text-white text-sm">Bildirimler</span>
        </div>
        @forelse(($header_notifications ?? []) as $n)
            <a href="{{ !empty($n->data['url']) ? $n->data['url'] : $header_notifications_url }}" class="block px-3 py-2.5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/80 {{ empty($n->read_at) ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}" role="menuitem">
                <p class="font-medium text-zinc-900 dark:text-white text-sm truncate">{{ $n->title ?? 'Bildirim' }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-2">{{ $n->message ?? '' }}</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ isset($n->created_at) ? $n->created_at->diffForHumans() : '' }}</p>
            </a>
        @empty
            <p class="px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">Bildirim yok.</p>
        @endforelse
        <div class="border-t border-zinc-100 dark:border-zinc-800 mt-1 pt-1 sticky bottom-0 bg-white dark:bg-zinc-900">
            <a href="{{ $header_notifications_url }}" class="block px-3 py-2.5 text-sm font-medium text-amber-600 dark:text-amber-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/80">Tümünü gör →</a>
        </div>
    </div>
</div>
@endif
@endauth
