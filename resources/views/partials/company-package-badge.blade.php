@php
    $company = $company ?? $firma ?? null;
    $config = $company?->getPackageConfig();
@endphp
@if($config)
    @php
        $badgeClass = match($config['id'] ?? '') {
            'kurumsal' => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/20 dark:border-amber-500/30',
            'profesyonel' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/20 dark:border-emerald-500/30',
            'baslangic' => 'bg-slate-100 dark:bg-zinc-700/50 text-slate-700 dark:text-zinc-300 border-slate-200 dark:border-zinc-600',
            default => 'bg-zinc-100 dark:bg-zinc-700/50 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-600',
        };
    @endphp
    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
        {{ $config['name'] ?? 'Paket' }}
    </span>
@endif
