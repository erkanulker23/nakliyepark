@extends('layouts.admin')

@section('title', 'Değerlendirmeler')
@section('page_heading', 'Değerlendirmeler')

@section('content')
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
                        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" class="inline" onsubmit="return confirm('Bu değerlendirmeyi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
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
