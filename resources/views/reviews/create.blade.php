@extends('layouts.app')

@section('title', 'Değerlendirme - NakliyePark')

@section('content')
<div class="px-4 py-6 max-w-lg mx-auto">
    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Taşıma Değerlendirmesi</h1>
    <p class="text-sm text-slate-500 mb-6">{{ $company->name }} — {{ $ihale->from_city }} → {{ $ihale->to_city }}</p>

    <form method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
        <input type="hidden" name="company_id" value="{{ $company->id }}">

        <div>
            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Puan (1-5)</label>
            <div class="flex gap-2">
                @for($i = 1; $i <= 5; $i++)
                    <label class="btn-touch w-12 h-12 rounded-full border-2 cursor-pointer flex items-center justify-center
                        {{ old('rating') == $i ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/30' : 'border-slate-300 dark:border-slate-600' }}">
                        <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} class="sr-only">
                        <span>{{ $i }}</span>
                    </label>
                @endfor
            </div>
            @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Yorum (opsiyonel)</label>
            <textarea name="comment" rows="3" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">{{ old('comment') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Teşekkür videosu (opsiyonel)</label>
            <p class="text-xs text-slate-500 mb-2">Telefonla çektiğiniz kısa video yükleyebilirsiniz. Max 50MB, mp4/mov.</p>
            <input type="file" name="video" accept="video/mp4,video/quicktime" capture="user"
                   class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
            @error('video')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-touch w-full bg-sky-500 text-white rounded-xl">Gönder</button>
    </form>
</div>
@endsection
