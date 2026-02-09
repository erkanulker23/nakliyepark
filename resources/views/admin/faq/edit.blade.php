@extends('layouts.admin')

@section('title', 'SSS düzenle')
@section('page_heading', 'SSS düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.faq.update', $faq) }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Soru *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('question')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cevap *</label>
            <textarea name="answer" rows="5" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">{{ old('answer', $faq->answer) }}</textarea>
            @error('answer')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Güncelle</button>
            <a href="{{ route('admin.faq.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500">İptal</a>
            <form method="POST" action="{{ route('admin.faq.destroy', $faq) }}" class="inline ml-auto" onsubmit="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">Sil</button>
            </form>
        </div>
    </form>
</div>
@endsection
