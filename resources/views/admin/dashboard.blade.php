@extends('layouts.admin')

@section('title', 'Kontrol Paneli')
@section('page_heading', 'Kontrol Paneli')

@section('content')
@php
    $hasAnyPending = $pendingCompanies->isNotEmpty() || $companiesWithPendingChanges->isNotEmpty() || $pendingIhaleler->isNotEmpty() || $tekliflerWithPendingUpdate->isNotEmpty() || $galleryImagesPendingCount > 0;
@endphp

{{-- Onay bekleyenler: tek ekranda, kart bazlı --}}
@if($hasAnyPending)
<div class="panel-card p-4 sm:p-5 mb-6 border-2 border-amber-200 dark:border-amber-800/60 bg-amber-50/50 dark:bg-amber-950/20">
    <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
        <span class="w-9 h-9 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        Onay bekleyenler
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        @if($pendingCompanies->isNotEmpty())
        <div class="panel-card p-4 rounded-2xl border border-amber-200/80 dark:border-amber-800/50 bg-white dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-bold">{{ $pendingCompaniesCount }}</span>
                Firma onayı (yeni)
            </h3>
            <ul class="space-y-1.5 text-sm">
                @foreach($pendingCompanies->take(5) as $c)
                    <li class="flex justify-between items-center gap-2">
                        <span class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $c->name }}</span>
                        <a href="{{ route('admin.companies.edit', $c) }}" class="shrink-0 text-amber-600 dark:text-amber-400 hover:underline font-medium text-xs">Onayla →</a>
                    </li>
                @endforeach
            </ul>
            @if($pendingCompaniesCount > 5)<p class="text-xs text-slate-500 dark:text-slate-400 mt-2">+{{ $pendingCompaniesCount - 5 }} daha</p>@endif
            <a href="{{ route('admin.companies.index') }}" class="inline-block mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Tüm firmalar →</a>
        </div>
        @endif

        @if($companiesWithPendingChanges->isNotEmpty())
        <div class="panel-card p-4 rounded-2xl border border-amber-200/80 dark:border-amber-800/50 bg-white dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-bold">{{ $companiesWithPendingChangesCount }}</span>
                Firma güncelleme talebi
            </h3>
            <ul class="space-y-1.5 text-sm">
                @foreach($companiesWithPendingChanges->take(5) as $c)
                    <li class="flex justify-between items-center gap-2">
                        <span class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $c->name }}</span>
                        <a href="{{ route('admin.companies.edit', $c) }}" class="shrink-0 text-amber-600 dark:text-amber-400 hover:underline font-medium text-xs">İncele →</a>
                    </li>
                @endforeach
            </ul>
            @if($companiesWithPendingChangesCount > 5)<p class="text-xs text-slate-500 dark:text-slate-400 mt-2">+{{ $companiesWithPendingChangesCount - 5 }} daha</p>@endif
            <a href="{{ route('admin.companies.index') }}" class="inline-block mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Tüm firmalar →</a>
        </div>
        @endif

        @if($pendingIhaleler->isNotEmpty())
        <div class="panel-card p-4 rounded-2xl border border-amber-200/80 dark:border-amber-800/50 bg-white dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-bold">{{ $pendingIhalelerCount }}</span>
                İhale onayı
            </h3>
            <ul class="space-y-1.5 text-sm">
                @foreach($pendingIhaleler->take(5) as $i)
                    <li class="flex justify-between items-center gap-2">
                        <span class="text-slate-800 dark:text-slate-200 truncate">{{ $i->from_city ?? '?' }} → {{ $i->to_city ?? '?' }}</span>
                        <a href="{{ route('admin.ihaleler.show', $i) }}" class="shrink-0 text-amber-600 dark:text-amber-400 hover:underline font-medium text-xs">Onayla →</a>
                    </li>
                @endforeach
            </ul>
            @if($pendingIhalelerCount > 5)<p class="text-xs text-slate-500 dark:text-slate-400 mt-2">+{{ $pendingIhalelerCount - 5 }} daha</p>@endif
            <a href="{{ route('admin.ihaleler.index', ['status' => 'pending']) }}" class="inline-block mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Tüm ihaleler →</a>
        </div>
        @endif

        @if($tekliflerWithPendingUpdate->isNotEmpty())
        <div class="panel-card p-4 rounded-2xl border border-amber-200/80 dark:border-amber-800/50 bg-white dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-bold">{{ $tekliflerWithPendingUpdateCount }}</span>
                Teklif güncelleme talebi
            </h3>
            <ul class="space-y-1.5 text-sm">
                @foreach($tekliflerWithPendingUpdate->take(5) as $t)
                    <li class="flex justify-between items-center gap-2">
                        <span class="text-slate-800 dark:text-slate-200 truncate">{{ $t->company->name ?? 'Firma' }} – {{ number_format($t->pending_amount ?? 0, 0, ',', '.') }} ₺</span>
                        <a href="{{ route('admin.teklifler.edit', $t) }}" class="shrink-0 text-amber-600 dark:text-amber-400 hover:underline font-medium text-xs">İncele →</a>
                    </li>
                @endforeach
            </ul>
            @if($tekliflerWithPendingUpdateCount > 5)<p class="text-xs text-slate-500 dark:text-slate-400 mt-2">+{{ $tekliflerWithPendingUpdateCount - 5 }} daha</p>@endif
            <a href="{{ route('admin.teklifler.index') }}" class="inline-block mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Tüm teklifler →</a>
        </div>
        @endif

        @if($galleryImagesPendingCount > 0)
        <div class="panel-card p-4 rounded-2xl border border-amber-200/80 dark:border-amber-800/50 bg-white dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-bold">{{ $galleryImagesPendingCount }}</span>
                Galeri görseli onayı
            </h3>
            @if($companiesWithUnapprovedImages->isNotEmpty())
                <ul class="space-y-1.5 text-sm">
                    @foreach($companiesWithUnapprovedImages->take(5) as $c)
                        <li><a href="{{ route('admin.companies.edit', $c) }}" class="text-amber-600 dark:text-amber-400 hover:underline font-medium">{{ $c->name }} →</a></li>
                    @endforeach
                </ul>
                @if($companiesWithUnapprovedImages->count() > 5)<p class="text-xs text-slate-500 dark:text-slate-400 mt-2">+{{ $companiesWithUnapprovedImages->count() - 5 }} firma daha</p>@endif
            @else
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $galleryImagesPendingCount }} görsel firma düzenleme sayfalarından onaylanabilir.</p>
            @endif
            <a href="{{ route('admin.companies.index') }}" class="inline-block mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Firmalar →</a>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Sayı kartları: sayılar öne çıkar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="panel-stat bg-gradient-to-br from-indigo-500 to-indigo-600 text-white border-0 shadow-lg shadow-indigo-500/20">
        <p class="panel-stat-value text-white text-2xl sm:text-3xl">{{ $stats['users'] }}</p>
        <p class="panel-stat-label text-indigo-100">Kullanıcılar</p>
    </div>
    <div class="panel-stat bg-gradient-to-br from-emerald-500 to-emerald-600 text-white border-0 shadow-lg shadow-emerald-500/20">
        <p class="panel-stat-value text-white text-2xl sm:text-3xl">{{ $stats['companies'] }}</p>
        <p class="panel-stat-label text-emerald-100">Firmalar</p>
    </div>
    <div class="panel-stat bg-gradient-to-br from-amber-500 to-amber-600 text-white border-0 shadow-lg shadow-amber-500/20">
        <p class="panel-stat-value text-white text-2xl sm:text-3xl">{{ $stats['companies_pending'] }}</p>
        <p class="panel-stat-label text-amber-100">Onay bekleyen</p>
    </div>
    <div class="panel-stat bg-gradient-to-br from-violet-500 to-violet-600 text-white border-0 shadow-lg shadow-violet-500/20">
        <p class="panel-stat-value text-white text-2xl sm:text-3xl">{{ $stats['ihaleler'] }}</p>
        <p class="panel-stat-label text-violet-100">Açık ihaleler</p>
    </div>
</div>

{{-- Özet satır: kullanıcılar + sayfa görüntülenmeleri --}}
<div class="flex flex-wrap gap-3 mb-6 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400">
    <span><strong class="text-slate-800 dark:text-slate-200">{{ $stats['users'] }}</strong> toplam kullanıcı</span>
    <span class="text-slate-400">|</span>
    <span><strong class="text-slate-800 dark:text-slate-200">{{ $stats['recent_users_7'] }}</strong> son 7 günde kayıt</span>
    <span class="text-slate-400">|</span>
    <span><strong class="text-slate-800 dark:text-slate-200">{{ $stats['companies_approved'] }}</strong> onaylı firma</span>
    <span class="text-slate-400">|</span>
    <span><strong class="text-slate-800 dark:text-slate-200">{{ number_format($stats['total_page_views'] ?? 0) }}</strong> toplam sayfa görüntülenmesi</span>
</div>

{{-- Kart listeler: görüntülenen firmalar, ihaleler, blog --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center text-sky-600 dark:text-sky-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </span>
            En çok görüntülenen firmalar
        </h2>
        <ul class="space-y-2 text-sm">
            @forelse($mostViewedCompanies as $c)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <a href="{{ route('admin.companies.edit', $c) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 truncate">{{ $c->name }}</a>
                    <span class="shrink-0 text-slate-500 dark:text-slate-400 ml-2">{{ number_format($c->view_count ?? 0) }} görüntülenme</span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz görüntülenme verisi yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tüm firmalar →</a>
    </div>
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </span>
            En çok görüntülenen ihaleler
        </h2>
        <ul class="space-y-2 text-sm">
            @forelse($mostViewedIhaleler as $i)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <a href="{{ route('admin.ihaleler.show', $i) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 truncate">{{ $i->from_city ?? '?' }} → {{ $i->to_city ?? '?' }}</a>
                    <span class="shrink-0 text-slate-500 dark:text-slate-400 ml-2">{{ number_format($i->view_count ?? 0) }} görüntülenme</span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz görüntülenme verisi yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.ihaleler.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tüm ihaleler →</a>
    </div>
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center text-rose-600 dark:text-rose-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </span>
            En çok görüntülenen blog yazıları
        </h2>
        <ul class="space-y-2 text-sm">
            @forelse($mostViewedBlogPosts ?? [] as $b)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <a href="{{ route('admin.blog.edit', $b) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 truncate">{{ $b->title }}</a>
                    <span class="shrink-0 text-slate-500 dark:text-slate-400 ml-2">{{ number_format($b->view_count ?? 0) }} görüntülenme</span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz blog görüntülenme verisi yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tüm blog yazıları →</a>
    </div>
</div>

{{-- Son firmalar, ihaleler, kullanıcılar: kart grid --}}
<div class="grid lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            Son firmalar
        </h2>
        <ul class="space-y-3">
            @forelse($recentCompanies as $c)
                <li class="flex justify-between items-center text-sm py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <span class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $c->name }}</span>
                    @if(!$c->approved_at)
                        <form id="form-approve-company-{{ $c->id }}" method="POST" action="{{ route('admin.companies.approve', $c) }}" class="inline">
                            @csrf
                            <input type="hidden" name="redirect" value="dashboard">
                            <button type="button" class="admin-dashboard-onayla-btn text-xs px-3 py-1.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 font-medium touch-manipulation" data-form-id="form-approve-company-{{ $c->id }}" data-label="{{ $c->name }}">Onayla</button>
                        </form>
                    @else
                        <x-panel.status-badge status="approved">Onaylı</x-panel.status-badge>
                    @endif
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz firma yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tümü →</a>
    </div>
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
            Son ihaleler
        </h2>
        <ul class="space-y-3 text-sm">
            @forelse($recentIhaleler as $i)
                <li class="py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <a href="{{ route('admin.ihaleler.show', $i) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium">{{ $i->from_location_text }} → {{ $i->to_location_text }}</a>
                    <span class="text-slate-500 dark:text-slate-400">({{ $i->user?->name ?? 'Misafir' }})</span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz ihale yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.ihaleler.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tümü →</a>
    </div>
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </span>
            Son kayıt olan kullanıcılar
        </h2>
        <ul class="space-y-3 text-sm">
            @forelse($recentUsers as $u)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700 last:border-0 gap-2">
                    <span class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $u->name }}</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-xs font-medium
                        @if($u->role === 'admin') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300
                        @elseif($u->role === 'nakliyeci') bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300
                        @else bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 @endif">
                        {{ $u->role === 'nakliyeci' ? 'Nakliyeci' : ($u->role === 'musteri' ? 'Müşteri' : 'Admin') }}
                    </span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz kullanıcı yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tüm kullanıcılar →</a>
    </div>
</div>

{{-- Özet grafik --}}
<div class="panel-card p-4 sm:p-6">
    <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Özet grafik</h2>
    <div class="w-full max-w-md h-[220px]">
        <canvas id="adminChart" width="400" height="220"></canvas>
    </div>
</div>

{{-- Onay modalı: "Onayla" tıklandığında "Onaylansın mı?" göster, onaylanırsa ilgili form submit (detaya gitmez) --}}
<div id="admin-dashboard-onay-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog" aria-labelledby="admin-onay-modal-title">
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-sm" id="admin-dashboard-onay-modal-backdrop"></div>
    <div class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm mx-4 p-5 rounded-2xl bg-[var(--panel-surface)] border border-[var(--panel-border)] shadow-xl">
        <h3 id="admin-onay-modal-title" class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Onay</h3>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Bu firmayı onaylamak istediğinize emin misiniz?</p>
        <p id="admin-onay-modal-label" class="text-sm font-medium text-slate-800 dark:text-slate-200 mb-4 truncate"></p>
        <div class="flex gap-3 justify-end">
            <button type="button" id="admin-dashboard-onay-modal-cancel" class="px-4 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">Hayır</button>
            <button type="button" id="admin-dashboard-onay-modal-confirm" class="px-4 py-2 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 font-medium">Evet, onayla</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script>
(function() {
    var modal = document.getElementById('admin-dashboard-onay-modal');
    var backdrop = document.getElementById('admin-dashboard-onay-modal-backdrop');
    var labelEl = document.getElementById('admin-onay-modal-label');
    var cancelBtn = document.getElementById('admin-dashboard-onay-modal-cancel');
    var confirmBtn = document.getElementById('admin-dashboard-onay-modal-confirm');
    var pendingFormId = null;

    function openModal(formId, label) {
        pendingFormId = formId;
        if (labelEl) labelEl.textContent = label || '';
        if (modal) {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        }
    }
    function closeModal() {
        pendingFormId = null;
        if (modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }
    }
    document.querySelectorAll('.admin-dashboard-onayla-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var formId = this.getAttribute('data-form-id');
            var label = this.getAttribute('data-label') || '';
            if (formId) openModal(formId, label);
        });
    });
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    if (confirmBtn) confirmBtn.addEventListener('click', function() {
        if (pendingFormId) {
            var form = document.getElementById(pendingFormId);
            if (form) form.submit();
        }
        closeModal();
    });
})();
</script>
<script>
(function() {
  var ctx = document.getElementById('adminChart');
  if (ctx && typeof Chart !== 'undefined') {
    new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Kullanıcılar', 'Firmalar', 'İhaleler'],
      datasets: [{
        label: 'Adet',
        data: [{{ $stats['users'] }}, {{ $stats['companies'] }}, {{ $stats['ihaleler'] }}],
        backgroundColor: ['#6366f1', '#10b981', '#f59e0b'],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
    });
  }
})();
</script>
@endpush
@endsection
