@extends('layouts.admin')

@section('title', 'Yeni sponsor')
@section('page_heading', 'Yeni sponsor ekle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.sponsors.store') }}" enctype="multipart/form-data" class="admin-card p-6 space-y-4">
        @csrf
        <div class="admin-form-group">
            <label class="admin-label">Şirket adı *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="admin-input" placeholder="Sponsor şirket adı">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Logo *</label>
            <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input py-2">
            @error('logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            <p class="mt-1 text-xs text-slate-500">Önerilen: PNG veya SVG, en fazla 2MB. Şeffaf arka plan tercih edilir.</p>
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Web sitesi URL (isteğe bağlı)</label>
            <input type="url" name="url" value="{{ old('url') }}" class="admin-input" placeholder="https://...">
            @error('url')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra (küçük önce)</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="admin-label mb-0">Aktif (anasayfada göster)</span>
            </label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Kaydet</button>
            <a href="{{ route('admin.sponsors.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
