@extends('layouts.admin')

@section('title', 'Defter Reklamları')
@section('page_heading', 'Defter reklamları')
@section('page_subtitle', 'Nakliyat defteri sayfasında rastgele gösterilir.')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.defter-reklamlari.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium text-sm">Yeni reklam</a>
</div>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-12">Sıra</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Başlık / Konum</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Durum</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700 dark:text-slate-300">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reklamlar as $r)
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-4 py-3 text-slate-500">{{ $r->sira }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium">{{ $r->baslik ?: '(Başlıksız)' }}</span>
                            <span class="text-slate-500 text-xs ml-1">({{ $r->konum }})</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($r->aktif)
                                <span class="text-emerald-600 dark:text-emerald-400">Aktif</span>
                            @else
                                <span class="text-slate-400">Pasif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.defter-reklamlari.edit', ['defter_reklamlari' => $r]) }}" class="text-sky-500 hover:underline mr-2">Düzenle</a>
                            <form method="POST" action="{{ route('admin.defter-reklamlari.destroy', ['defter_reklamlari' => $r]) }}" class="inline" onsubmit="return confirm('Bu reklamı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Reklam yok. <a href="{{ route('admin.defter-reklamlari.create') }}" class="text-sky-500 hover:underline">İlk reklamı ekleyin</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reklamlar->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $reklamlar->links() }}</div>
    @endif
</div>
@endsection
