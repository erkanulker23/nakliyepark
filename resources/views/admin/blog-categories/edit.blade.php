@extends('layouts.admin')

@section('title', 'Kategori düzenle')
@section('page_heading', 'Kategori düzenle')
@section('page_subtitle', $blog_category->name)

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.blog-categories.update', $blog_category) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Kategori adı *</label>
                <input type="text" name="name" value="{{ old('name', $blog_category->name) }}" required class="admin-input">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $blog_category->slug) }}" class="admin-input">
                @error('slug')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="3" class="admin-input">{{ old('description', $blog_category->description) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Sıra</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $blog_category->sort_order) }}" min="0" class="admin-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="admin-btn-secondary">İptal</a>
                <form method="POST" action="{{ route('admin.blog-categories.destroy', $blog_category) }}" class="inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">Sil</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
