@extends('layouts.admin')

@section('title', 'SSS düzenle')
@section('page_heading', 'SSS düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.faq.update', $faq) }}" class="admin-card p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
            <label class="admin-label">Soru *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="admin-input">
            @error('question')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Cevap *</label>
            <textarea name="answer" rows="5" required class="admin-input">{{ old('answer', $faq->answer) }}</textarea>
            @error('answer')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Hedef kitle</label>
            <select name="audience" class="admin-input">
                <option value="">Hepsi (müşteri + nakliyeci)</option>
                <option value="musteri" {{ old('audience', $faq->audience) === 'musteri' ? 'selected' : '' }}>Müşteri</option>
                <option value="nakliyeci" {{ old('audience', $faq->audience) === 'nakliyeci' ? 'selected' : '' }}>Nakliyeci</option>
            </select>
            @error('audience')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Güncelle</button>
            <a href="{{ route('admin.faq.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.faq.destroy', $faq) }}" class="inline mt-3" onsubmit="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn-danger">Sil</button>
    </form>
</div>
@endsection
