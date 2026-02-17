@extends('layouts.admin')

@section('title', 'KVKK Açık Rıza Logları')
@section('page_heading', 'KVKK Açık Rıza Logları')
@section('page_subtitle', 'Hangi IP ve tarihte rıza verildi')

@section('content')
@if(session('success'))
    <div class="admin-alert-success mb-4">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('admin.consent-logs.index') }}" class="admin-card p-4 mb-4 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Rıza tipi</label>
        <select name="consent_type" class="admin-input">
            <option value="">Tümü</option>
            <option value="kvkk_ihale" {{ request('consent_type') === 'kvkk_ihale' ? 'selected' : '' }}>İhale formu (KVKK)</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">IP</label>
        <input type="text" name="ip" class="admin-input w-40" placeholder="IP" value="{{ request('ip') }}">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Başlangıç</label>
        <input type="date" name="date_from" class="admin-input" value="{{ request('date_from') }}">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Bitiş</label>
        <input type="date" name="date_to" class="admin-input" value="{{ request('date_to') }}">
    </div>
    <button type="submit" class="admin-btn-primary">Filtrele</button>
</form>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Tip</th>
                    <th>IP</th>
                    <th>Kullanıcı</th>
                    <th>İhale</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap">{{ $log->consented_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $log->consent_type }}</td>
                        <td class="font-mono text-sm">{{ $log->ip ?? '—' }}</td>
                        <td>
                            @if($log->user_id && $log->user)
                                <a href="{{ route('admin.users.edit', $log->user) }}" class="text-sky-600 hover:underline">{{ $log->user->name }}</a>
                                <span class="text-slate-400 text-xs">({{ $log->user->email }})</span>
                            @else
                                <span class="text-slate-500">Misafir</span>
                            @endif
                        </td>
                        <td>
                            @if($log->ihale_id && $log->ihale)
                                <a href="{{ route('admin.ihaleler.show', $log->ihale) }}" class="text-sky-600 hover:underline">{{ $log->ihale->from_location_text }} → {{ $log->ihale->to_location_text }}</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-500 py-8">Kayıt yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-4 border-t border-slate-200">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
