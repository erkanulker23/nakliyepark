@extends('layouts.admin')

@section('title', 'Uyuşmazlıklar')
@section('page_heading', 'Uyuşmazlıklar')
@section('page_subtitle', 'İhale / firma şikâyet ve itirazları')

@section('content')
@if(session('success'))
    <div class="admin-alert-success mb-4">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('admin.disputes.index') }}" class="admin-card p-4 mb-4 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Durum</label>
        <select name="status" class="admin-input">
            <option value="">Tümü</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Açık</option>
            <option value="admin_review" {{ request('status') === 'admin_review' ? 'selected' : '' }}>İnceleniyor</option>
            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Çözüldü</option>
        </select>
    </div>
    <button type="submit" class="admin-btn-primary">Filtrele</button>
</form>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>İhale</th>
                    <th>Firma</th>
                    <th>Açan</th>
                    <th>Sebep</th>
                    <th>Durum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($disputes as $d)
                    <tr>
                        <td class="whitespace-nowrap">{{ $d->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.ihaleler.show', $d->ihale) }}" class="text-sky-600 hover:underline">{{ $d->ihale->from_city }} → {{ $d->ihale->to_city }}</a>
                        </td>
                        <td>{{ $d->company->name ?? '-' }}</td>
                        <td>{{ $d->openedByUser->name ?? '-' }} <span class="text-slate-400 text-xs">({{ $d->opened_by_type }})</span></td>
                        <td>{{ \App\Models\Dispute::reasonLabels()[$d->reason] ?? $d->reason }}</td>
                        <td>
                            @if($d->status === 'open')<span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-xs">Açık</span>
                            @elseif($d->status === 'admin_review')<span class="px-2 py-0.5 rounded bg-sky-100 text-sky-800 text-xs">İnceleniyor</span>
                            @else<span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs">Çözüldü</span>
                            @endif
                        </td>
                        <td><a href="{{ route('admin.disputes.show', $d) }}" class="admin-btn-secondary text-sm py-1 px-2">Detay</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-slate-500 py-8">Uyuşmazlık kaydı yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($disputes->hasPages())
        <div class="p-4 border-t border-slate-200">{{ $disputes->links() }}</div>
    @endif
</div>
@endsection
