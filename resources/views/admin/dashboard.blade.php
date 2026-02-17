@extends('layouts.admin')

@section('title', 'Kontrol Paneli')
@section('page_heading', 'Kontrol Paneli')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
    <div class="p-6 rounded-2xl border-0 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-100">Kullanıcılar</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['users'] }}</p>
            </div>
            <span class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </span>
        </div>
    </div>
    <div class="p-6 rounded-2xl border-0 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-100">Firmalar</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['companies'] }}</p>
            </div>
            <span class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
        </div>
    </div>
    <div class="p-6 rounded-2xl border-0 bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-amber-100">Onay bekleyen firmalar</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['companies_pending'] }}</p>
            </div>
            <span class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
    </div>
    <div class="p-6 rounded-2xl border-0 bg-gradient-to-br from-violet-500 to-violet-600 text-white shadow-lg shadow-violet-500/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-violet-100">Açık ihaleler</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['ihaleler'] }}</p>
            </div>
            <span class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
        </div>
    </div>
</div>

<div class="flex flex-wrap gap-4 mb-6 p-4 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
    <span class="text-sm text-slate-600 dark:text-slate-400"><strong class="text-slate-800 dark:text-slate-200">{{ $stats['users'] }}</strong> toplam kullanıcı</span>
    <span class="text-slate-400 dark:text-slate-500">|</span>
    <span class="text-sm text-slate-600 dark:text-slate-400"><strong class="text-slate-800 dark:text-slate-200">{{ $stats['recent_users_7'] }}</strong> son 7 günde kayıt</span>
    <span class="text-slate-400 dark:text-slate-500">|</span>
    <span class="text-sm text-slate-600 dark:text-slate-400"><strong class="text-slate-800 dark:text-slate-200">{{ $stats['companies_approved'] }}</strong> sistemde aktif (onaylı) firma</span>
    <span class="text-slate-400 dark:text-slate-500">|</span>
    <span class="text-sm text-slate-600 dark:text-slate-400"><strong class="text-slate-800 dark:text-slate-200">{{ $stats['companies'] }}</strong> toplam firma</span>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="admin-card p-6 rounded-2xl">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            Son firmalar
        </h2>
        <ul class="space-y-3">
            @forelse($recentCompanies as $c)
                <li class="flex justify-between items-center text-sm py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $c->name }}</span>
                    @if(!$c->approved_at)
                        <form method="POST" action="{{ route('admin.companies.approve', $c) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Onayla</button>
                        </form>
                    @else
                        <span class="text-emerald-600 dark:text-emerald-400 text-xs font-medium">Onaylı</span>
                    @endif
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz firma yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm admin-dashboard-link hover:underline font-medium">Tümü →</a>
    </div>
    <div class="admin-card p-6 rounded-2xl">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400">
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
        <a href="{{ route('admin.ihaleler.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm admin-dashboard-link hover:underline font-medium">Tümü →</a>
    </div>
    <div class="admin-card p-6 rounded-2xl">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </span>
            Son kayıt olan kullanıcılar
        </h2>
        <ul class="space-y-3 text-sm">
            @forelse($recentUsers as $u)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700 last:border-0 gap-2">
                    <span class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $u->name }}</span>
                    <span class="flex items-center gap-2 shrink-0">
                        <span class="text-slate-500 dark:text-slate-400 text-xs hidden sm:inline">{{ $u->email }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                            @if($u->role === 'admin') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300
                            @elseif($u->role === 'nakliyeci') bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300
                            @else bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 @endif">
                            {{ $u->role === 'nakliyeci' ? 'Nakliyeci' : ($u->role === 'musteri' ? 'Müşteri' : 'Admin') }}
                        </span>
                    </span>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz kullanıcı yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 mt-4 text-sm admin-dashboard-link hover:underline font-medium">Tüm kullanıcılar →</a>
    </div>
</div>

<div class="admin-card p-6 rounded-2xl">
    <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Özet grafik</h2>
    <div class="w-full max-w-md" style="height: 220px;">
        <canvas id="adminChart" width="400" height="220"></canvas>
    </div>
</div>

@push('scripts')
<script type="module">
import Chart from 'chart.js/auto';
const ctx = document.getElementById('adminChart');
if (ctx) {
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Kullanıcılar', 'Firmalar', 'İhaleler'],
      datasets: [{
        label: 'Adet (Kullanıcı, Firma, İhale)',
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
</script>
@endpush
@endsection
