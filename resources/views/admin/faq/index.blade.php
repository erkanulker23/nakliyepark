@extends('layouts.admin')

@section('title', 'SSS')
@section('page_heading', 'Sıkça Sorulan Sorular')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.faq.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium text-sm">Yeni SSS</a>
</div>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-12">Sıra</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Soru</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-28">Hedef</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700 dark:text-slate-300">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $f)
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-4 py-3 text-slate-500">{{ $f->sort_order }}</td>
                        <td class="px-4 py-3 font-medium">{{ Str::limit($f->question, 60) }}</td>
                        <td class="px-4 py-3 text-slate-500 text-sm">{{ $f->audience === 'musteri' ? 'Müşteri' : ($f->audience === 'nakliyeci' ? 'Nakliyeci' : 'Hepsi') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.faq.edit', $f) }}" class="text-sky-500 hover:underline mr-2">Düzenle</a>
                            <form method="POST" action="{{ route('admin.faq.destroy', $f) }}" class="inline" onsubmit="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">SSS yok. <a href="{{ route('admin.faq.create') }}" class="text-sky-500 hover:underline">İlk soruyu ekleyin</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($faqs->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $faqs->links() }}</div>
    @endif
</div>
@endsection
