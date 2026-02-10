@extends('layouts.admin')

@section('title', 'Yeni SSS')
@section('page_heading', 'Yeni SSS')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.faq.store') }}" class="admin-card p-6 space-y-4">
        @csrf
        <div class="admin-form-group">
            <label class="admin-label">Soru *</label>
            <input type="text" name="question" value="{{ old('question') }}" required class="admin-input">
            @error('question')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Cevap *</label>
            <textarea name="answer" rows="5" required class="admin-input">{{ old('answer') }}</textarea>
            @error('answer')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra (küçük önce)</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Kaydet</button>
            <a href="{{ route('admin.faq.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
