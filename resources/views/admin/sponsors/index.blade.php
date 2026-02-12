@extends('layouts.admin')

@section('title', 'Sponsorlar')
@section('page_heading', 'Sponsorlarımız')
@section('page_subtitle', 'Anasayfada görünen sponsor logolarını yönetin')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.sponsors.create') }}" class="admin-btn-primary">Yeni Sponsor</a>
</div>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-16">Logo</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Şirket</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-28">Sıra</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-24">Durum</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700 dark:text-slate-300">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sponsors as $s)
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-4 py-3">
                            @if($s->logo)
                                <img src="{{ asset('storage/'.$s->logo) }}" alt="{{ $s->name }}" class="w-12 h-12 object-contain rounded-lg bg-slate-100 dark:bg-slate-700">
                            @else
                                <span class="w-12 h-12 rounded-lg bg-slate-200 dark:bg-slate-600 flex items-center justify-center text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $s->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $s->sort_order }}</td>
                        <td class="px-4 py-3">
                            @if($s->is_active)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">Pasif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.sponsors.edit', $s) }}" class="text-sky-500 hover:underline mr-2">Düzenle</a>
                            <form method="POST" action="{{ route('admin.sponsors.destroy', $s) }}" class="inline" onsubmit="return confirm('Bu sponsoru silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                            Henüz sponsor yok. <a href="{{ route('admin.sponsors.create') }}" class="text-sky-500 hover:underline">İlk sponsoru ekleyin</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sponsors->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $sponsors->links() }}</div>
    @endif
</div>
@endsection
