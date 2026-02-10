@extends('layouts.admin')

@section('title', 'Değerlendirme Düzenle')
@section('page_heading', 'Değerlendirme Düzenle')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-400">
            <div><span class="font-medium text-slate-700 dark:text-slate-300">Kullanıcı:</span> {{ $review->user->name ?? '-' }}</div>
            <div><span class="font-medium text-slate-700 dark:text-slate-300">İhale:</span> {{ $review->ihale ? ($review->ihale->from_city . ' → ' . $review->ihale->to_city) : '-' }}</div>
            <div><span class="font-medium text-slate-700 dark:text-slate-300">Tarih:</span> {{ $review->created_at->format('d.m.Y H:i') }}</div>
        </div>

        <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-600">
            @csrf
            @method('PUT')

            <div class="admin-form-group">
                <label for="company_search" class="admin-label">Değerlendirilen firma *</label>
                <input type="text" id="company_search" autocomplete="off" placeholder="Firma ara (ad veya şehir yazın)..." class="admin-input mb-2">
                <select name="company_id" id="company_id" required class="admin-input">
                    <option value="">Firma seçin</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" data-search="{{ strtolower($c->name . ' ' . ($c->city ?? '')) }}" {{ old('company_id', $review->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}{{ $c->city ? ' (' . $c->city . ')' : '' }}</option>
                    @endforeach
                </select>
                @error('company_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <script>
            (function() {
                var search = document.getElementById('company_search');
                var select = document.getElementById('company_id');
                if (!search || !select) return;
                search.addEventListener('input', function() {
                    var q = this.value.trim().toLowerCase();
                    var opts = select.querySelectorAll('option');
                    opts.forEach(function(opt) {
                        if (opt.value === '') { opt.hidden = false; return; }
                        opt.hidden = q === '' ? false : !(opt.dataset.search || opt.textContent).toLowerCase().includes(q);
                    });
                });
                search.addEventListener('focus', function() { select.style.pointerEvents = 'none'; });
                search.addEventListener('blur', function() { setTimeout(function() { select.style.pointerEvents = ''; }, 200); });
            })();
            </script>

            <div class="admin-form-group">
                <label for="rating" class="admin-label">Puan (1-5)</label>
                <select name="rating" id="rating" class="admin-input" required>
                    @foreach(range(1, 5) as $n)
                        <option value="{{ $n }}" {{ old('rating', $review->rating) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="comment" class="admin-label">Yorum</label>
                <textarea name="comment" id="comment" rows="4" class="admin-input" maxlength="2000">{{ old('comment', $review->comment) }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="video_path" class="admin-label">Video URL (isteğe bağlı)</label>
                <input type="text" name="video_path" id="video_path" value="{{ old('video_path', $review->video_path) }}" class="admin-input" placeholder="https://...">
                @error('video_path')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
