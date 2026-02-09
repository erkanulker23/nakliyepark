@extends('layouts.admin')

@section('title', 'Yeni blog kategorisi')
@section('page_heading', 'Yeni blog kategorisi')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.blog-categories.store') }}" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Kategori adı *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="admin-input">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Slug (boş bırakırsanız adından üretilir)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input">
                @error('slug')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="3" class="admin-input">{{ old('description') }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Sıra</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="admin-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
