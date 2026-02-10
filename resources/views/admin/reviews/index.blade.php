@extends('layouts.admin')

@section('title', 'Değerlendirmeler')
@section('page_heading', 'Değerlendirmeler')

@section('content')
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Kullanıcı, firma veya yorum ara..." class="admin-input py-2 w-56 text-sm">
        <select name="rating" class="admin-input py-2 w-32 text-sm">
            <option value="">Tüm puanlar</option>
            @for($r = 5; $r >= 1; $r--)
                <option value="{{ $r }}" {{ (isset($filters['rating']) && (string)$filters['rating'] === (string)$r) ? 'selected' : '' }}>{{ $r }}/5</option>
            @endfor
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.reviews.index') }}" class="admin-btn-secondary text-sm py-2">Temizle</a>
        @endif
    </form>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Kullanıcı</th>
                <th>Firma</th>
                <th>Puan</th>
                <th>Yorum</th>
                <th>Tarih</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $r)
                <tr>
                    <td>{{ $r->user->name ?? '-' }}</td>
                    <td>{{ $r->company->name ?? '-' }}</td>
                    <td>{{ $r->rating }}/5</td>
                    <td class="max-w-xs truncate text-slate-600">{{ Str::limit($r->comment, 50) }}</td>
                    <td class="text-slate-500 text-sm">{{ $r->created_at->format('d.m.Y') }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.reviews.edit', $r) }}" class="text-sky-500 hover:underline mr-2 text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" class="inline" onsubmit="return confirm('Bu değerlendirmeyi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <input type="text" name="action_reason" class="admin-input py-1 w-32 text-xs mr-1" placeholder="Neden (opsiyonel)" maxlength="1000">
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Değerlendirme yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reviews->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
