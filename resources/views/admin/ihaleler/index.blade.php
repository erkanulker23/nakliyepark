@extends('layouts.admin')

@section('title', 'İhaleler')
@section('page_heading', 'İhaleler')

@section('content')
@php
    $pendingCount = \App\Models\Ihale::where('status', 'pending')->count();
    $statusLabels = ['pending' => 'Onay bekliyor', 'draft' => 'Taslak', 'published' => 'Yayında', 'closed' => 'Kapalı', 'cancelled' => 'İptal'];
@endphp
@if($pendingCount > 0)
    <div class="panel-card p-4 rounded-2xl mb-6 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800">
        <strong>{{ $pendingCount }}</strong> ihale onay bekliyor.
        <a href="{{ route('admin.ihaleler.index', ['status' => 'pending']) }}" class="font-medium text-amber-700 dark:text-amber-400 hover:underline ml-1">Görüntüle</a>
    </div>
@endif

<form method="get" action="{{ route('admin.ihaleler.index') }}" class="panel-card p-4 rounded-2xl mb-6 min-w-0">
    <div class="flex flex-wrap items-center gap-3 min-w-0">
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="input-touch w-auto min-w-[130px] rounded-xl text-sm" aria-label="Başlangıç">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="input-touch w-auto min-w-[130px] rounded-xl text-sm" aria-label="Bitiş">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Ara..." class="input-touch flex-1 min-w-[160px] max-w-xs rounded-xl text-sm">
        <input type="text" name="from_city" value="{{ $filters['from_city'] ?? '' }}" placeholder="Nereden" class="input-touch w-auto min-w-[100px] rounded-xl text-sm">
        <input type="text" name="to_city" value="{{ $filters['to_city'] ?? '' }}" placeholder="Nereye" class="input-touch w-auto min-w-[100px] rounded-xl text-sm">
        <select name="status" class="input-touch w-auto min-w-[130px] rounded-xl text-sm">
            <option value="">Tüm durumlar</option>
            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Onay bekliyor</option>
            <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Yayında</option>
            <option value="closed" {{ ($filters['status'] ?? '') === 'closed' ? 'selected' : '' }}>Kapalı</option>
        </select>
        <button type="submit" class="btn-secondary rounded-xl text-sm py-2.5 px-4">Filtrele</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.ihaleler.index') }}" class="btn-ghost rounded-xl text-sm py-2.5 px-4">Temizle</a>
        @endif
        <a href="{{ route('admin.ihaleler.create') }}" class="btn-primary rounded-xl text-sm py-2.5 px-4 ml-auto">Yeni ihale</a>
    </div>
</form>

<div class="mb-4 flex flex-wrap items-center gap-3">
    <label class="flex items-center gap-2 text-sm text-[var(--panel-text)] cursor-pointer">
        <input type="checkbox" id="select-all" class="rounded border-slate-300 text-[var(--panel-primary)] focus:ring-[var(--panel-primary)]" title="Tümünü seç">
        <span>Tümünü seç</span>
    </label>
    <form method="POST" action="{{ route('admin.ihaleler.bulk-publish') }}" id="form-bulk-publish" class="inline">
        @csrf
        <input type="hidden" name="ids" id="bulk-ids-publish" value="">
        <button type="submit" class="btn-secondary rounded-xl text-sm py-2 px-3" onclick="setBulkIds('bulk-ids-publish'); return document.getElementById('bulk-ids-publish').value;">Seçilenleri yayınla</button>
    </form>
    <form method="POST" action="{{ route('admin.ihaleler.bulk-close') }}" id="form-bulk-close" class="inline">
        @csrf
        <input type="hidden" name="ids" id="bulk-ids-close" value="">
        <button type="submit" class="btn-secondary rounded-xl text-sm py-2 px-3" onclick="setBulkIds('bulk-ids-close'); return document.getElementById('bulk-ids-close').value;">Seçilenleri kapat</button>
    </form>
    <span class="text-sm text-[var(--panel-text-muted)]">Toplu işlem için kartları işaretleyin.</span>
</div>

<div class="space-y-4">
    @forelse($ihaleler as $i)
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex items-start gap-3 min-w-0 flex-1">
                    @if($i->status === 'pending' || $i->status === 'published')
                        <input type="checkbox" class="bulk-ihale-id mt-1 rounded border-slate-300 text-[var(--panel-primary)] focus:ring-[var(--panel-primary)]" value="{{ $i->id }}" data-status="{{ $i->status }}" aria-label="Seç">
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-lg font-bold text-[var(--panel-text)]">
                            {{ $i->from_location_text }} → {{ $i->to_location_text }}
                        </h3>
                        <p class="text-sm text-[var(--panel-text-muted)] mt-0.5">{{ $i->user?->name ?? $i->guest_contact_name ?? 'Misafir' }}</p>
                        <p class="text-sm text-[var(--panel-text-muted)] mt-1">
                            @if($i->move_date || $i->move_date_end)
                                @if($i->move_date_end && $i->move_date != $i->move_date_end)
                                    {{ $i->move_date?->format('d.m.Y') }} – {{ $i->move_date_end?->format('d.m.Y') }}
                                @else
                                    {{ $i->move_date?->format('d.m.Y') ?? '-' }}
                                @endif
                            @else
                                Fiyat bakıyorum
                            @endif
                            · {{ $i->volume_m3 }} m³
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <x-panel.status-badge :status="$i->status === 'pending' ? 'pending' : ($i->status === 'published' ? 'approved' : 'neutral')">
                        {{ $statusLabels[$i->status] ?? $i->status }}
                    </x-panel.status-badge>
                    <a href="{{ route('admin.ihaleler.show', $i) }}" class="btn-secondary rounded-xl text-sm py-2 px-4">Detay</a>
                    <a href="{{ route('admin.ihaleler.edit', $i) }}" class="btn-ghost rounded-xl text-sm py-2 px-4">Düzenle</a>
                    <form method="POST" action="{{ route('admin.ihaleler.destroy', $i) }}" class="inline" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
            <p class="text-[var(--panel-text-muted)]">İhale yok.</p>
        </div>
    @endforelse
</div>

<script>
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.bulk-ihale-id').forEach(function(cb) { cb.checked = this.checked; }.bind(this));
});
function setBulkIds(hiddenId) {
    var ids = Array.from(document.querySelectorAll('.bulk-ihale-id:checked')).map(function(c) { return c.value; });
    document.getElementById(hiddenId).value = ids.join(',');
}
</script>
@if($ihaleler->hasPages())
    <div class="mt-8">{{ $ihaleler->links() }}</div>
@endif
@endsection
