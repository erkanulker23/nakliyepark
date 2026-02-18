@extends('layouts.admin')

@section('title', 'Kullanıcılar')
@section('page_heading', 'Kullanıcılar')

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="{{ route('admin.users.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni kullanıcı
    </a>
</div>

<div class="mb-4 flex flex-wrap items-center gap-3">
    <label class="flex items-center gap-2 text-sm text-[var(--panel-text)] cursor-pointer">
        <input type="checkbox" id="select-all-users" class="rounded border-slate-300 text-[var(--panel-primary)] focus:ring-[var(--panel-primary)]" title="Tümünü seç">
        <span>Tümünü seç</span>
    </label>
    <form method="POST" action="{{ route('admin.users.bulk-delete') }}" id="form-bulk-delete-users" class="inline" onsubmit="setBulkUserIds(); return document.getElementById('bulk-user-ids').value && confirm('Seçilen kullanıcıları silmek istediğinize emin misiniz? Bu işlem geri alınamaz.');">
        @csrf
        <input type="hidden" name="ids" id="bulk-user-ids" value="">
        <button type="submit" class="btn-secondary rounded-xl text-sm py-2 px-3 text-red-600 dark:text-red-400">Seçilenleri sil</button>
    </form>
    <span class="text-sm text-[var(--panel-text-muted)]">Toplu silme için kartları işaretleyin.</span>
</div>

<div class="space-y-4">
    @forelse($users as $u)
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex items-start gap-3 min-w-0 flex-1">
                    @if($u->id !== auth()->id())
                        <input type="checkbox" class="bulk-user-id mt-1 rounded border-slate-300 text-[var(--panel-primary)] focus:ring-[var(--panel-primary)]" value="{{ $u->id }}" aria-label="Seç">
                    @endif
                    <div class="min-w-0">
                    <h3 class="text-lg font-bold text-[var(--panel-text)]">{{ $u->name }}</h3>
                    <p class="text-sm text-[var(--panel-text-muted)] mt-0.5">{{ $u->email }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <x-panel.status-badge :status="$u->role === 'admin' ? 'approved' : ($u->role === 'nakliyeci' ? 'neutral' : 'pending')">
                            {{ $u->role === 'nakliyeci' ? 'Nakliyeci' : ($u->role === 'musteri' ? 'Müşteri' : 'Admin') }}
                        </x-panel.status-badge>
                        @if($u->role === 'nakliyeci' && $u->company)
                            <a href="{{ route('admin.companies.edit', $u->company) }}" class="text-sm font-medium text-[var(--panel-primary)] hover:underline">{{ $u->company->name }}</a>
                            @if(!$u->company->approved_at)
                                <span class="text-xs text-amber-600 dark:text-amber-400">(onay bekliyor)</span>
                                <form method="POST" action="{{ route('admin.companies.approve', $u->company) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-2 py-1 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">Onayla</button>
                                </form>
                            @endif
                        @elseif($u->role === 'musteri')
                            <span class="text-sm text-[var(--panel-text-muted)]">{{ $u->ihaleler_count ?? 0 }} ihale</span>
                        @endif
                    </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn-secondary rounded-xl text-sm py-2.5 px-4">Düzenle</a>
                    @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Sil</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
            <p class="text-[var(--panel-text-muted)]">Kullanıcı yok.</p>
        </div>
    @endforelse
</div>

<script>
document.getElementById('select-all-users')?.addEventListener('change', function() {
    document.querySelectorAll('.bulk-user-id').forEach(function(cb) { cb.checked = this.checked; }.bind(this));
});
function setBulkUserIds() {
    var ids = Array.from(document.querySelectorAll('.bulk-user-id:checked')).map(function(c) { return c.value; });
    document.getElementById('bulk-user-ids').value = ids.join(',');
}
</script>
@if($users->hasPages())
    <div class="mt-8">{{ $users->links() }}</div>
@endif
@endsection
