@extends('layouts.admin')

@section('title', 'Sponsor düzenle')
@section('page_heading', 'Sponsor düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.sponsors.update', $sponsor) }}" enctype="multipart/form-data" class="admin-card p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
            <label class="admin-label">Şirket adı *</label>
            <input type="text" name="name" value="{{ old('name', $sponsor->name) }}" required class="admin-input" placeholder="Sponsor şirket adı">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Logo</label>
            @if($sponsor->logo)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ asset('storage/'.$sponsor->logo) }}" alt="{{ $sponsor->name }}" class="w-20 h-20 object-contain rounded-lg bg-slate-100 dark:bg-slate-700">
                    <span class="text-sm text-slate-500">Mevcut logo. Yeni yüklerseniz değişir.</span>
                </div>
            @endif
            <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input py-2">
            @error('logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Web sitesi URL (isteğe bağlı)</label>
            <input type="url" name="url" value="{{ old('url', $sponsor->url) }}" class="admin-input" placeholder="https://...">
            @error('url')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $sponsor->sort_order) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $sponsor->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="admin-label mb-0">Aktif (anasayfada göster)</span>
            </label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Güncelle</button>
            <a href="{{ route('admin.sponsors.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}" class="inline mt-3" onsubmit="return confirm('Bu sponsoru silmek istediğinize emin misiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn-danger">Sil</button>
    </form>
</div>
@endsection
