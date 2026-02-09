@extends('layouts.nakliyeci')

@section('title', 'Şirket evrakları')
@section('page_heading', 'Şirket evrakları')
@section('page_subtitle', 'K1, sigorta, ruhsat vb.')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Firmanıza ait evrakları yükleyin. Süresi dolan evrakları güncelleyin.</p>
        <a href="{{ route('nakliyeci.evraklar.create') }}" class="admin-btn-primary inline-flex">+ Evrak yükle</a>
    </div>
    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Evrak</th>
                    <th>Son geçerlilik</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $doc->type_label }}</span>
                            @if($doc->title && $doc->title !== $doc->type_label)
                                <span class="block text-sm text-slate-500">{{ $doc->title }}</span>
                            @endif
                        </td>
                        <td class="text-slate-600 dark:text-slate-400">
                            @if($doc->expires_at)
                                {{ $doc->expires_at->format('d.m.Y') }}
                                @if($doc->expires_at->isPast())
                                    <span class="text-red-600 text-xs">(Süresi doldu)</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-sm text-emerald-600 hover:underline mr-3">Görüntüle</a>
                            <form method="POST" action="{{ route('nakliyeci.evraklar.destroy', $doc->id) }}" class="inline" onsubmit="return confirm('Bu evrakı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-slate-500">Henüz evrak yüklenmedi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
