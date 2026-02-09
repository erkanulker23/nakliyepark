@extends('layouts.admin')

@section('title', 'Oda şablonları')
@section('page_heading', 'Oda şablonları')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.room-templates.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium text-sm">Yeni şablon</a>
</div>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-12">Sıra</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Ad</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Varsayılan hacim (m³)</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700 dark:text-slate-300">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $t)
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-4 py-3 text-slate-500">{{ $t->sort_order }}</td>
                        <td class="px-4 py-3 font-medium">{{ $t->name }}</td>
                        <td class="px-4 py-3">{{ $t->default_volume_m3 }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.room-templates.edit', $t) }}" class="text-sky-500 hover:underline mr-2">Düzenle</a>
                            <form method="POST" action="{{ route('admin.room-templates.destroy', $t) }}" class="inline" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Oda şablonu yok. <a href="{{ route('admin.room-templates.create') }}" class="text-sky-500 hover:underline">İlk şablonu ekleyin</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($templates->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $templates->links() }}</div>
    @endif
</div>
@endsection
