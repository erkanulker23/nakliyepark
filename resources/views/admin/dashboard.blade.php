@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Kullanıcılar</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['users'] }}</p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Firmalar</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['companies'] }}</p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Onay bekleyen firmalar</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['companies_pending'] }}</p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Açık ihaleler</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['ihaleler'] }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 mb-3">Son firmalar</h2>
        <ul class="space-y-2">
            @forelse($recentCompanies as $c)
                <li class="flex justify-between items-center text-sm">
                    <span>{{ $c->name }}</span>
                    @if(!$c->approved_at)
                        <form method="POST" action="{{ route('admin.companies.approve', $c) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-2 py-1 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">Onayla</button>
                        </form>
                    @else
                        <span class="text-emerald-600 text-xs font-medium">Onaylı</span>
                    @endif
                </li>
            @empty
                <li class="text-slate-500">Henüz firma yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.companies.index') }}" class="block mt-3 text-sm text-indigo-600 hover:underline font-medium">Tümü →</a>
    </div>
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 mb-3">Son ihaleler</h2>
        <ul class="space-y-2 text-sm">
            @forelse($recentIhaleler as $i)
                <li>
                    <a href="{{ route('admin.ihaleler.show', $i) }}" class="text-indigo-600 hover:underline">{{ $i->from_city }} → {{ $i->to_city }}</a>
                    <span class="text-slate-500">({{ $i->user?->name ?? 'Misafir' }})</span>
                </li>
            @empty
                <li class="text-slate-500">Henüz ihale yok.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.ihaleler.index') }}" class="block mt-3 text-sm text-indigo-600 hover:underline font-medium">Tümü →</a>
    </div>
</div>

<div class="admin-card p-6">
    <h2 class="font-semibold text-slate-800 mb-3">Özet</h2>
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
</script>
@endpush
@endsection
