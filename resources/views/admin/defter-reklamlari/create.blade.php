@extends('layouts.admin')

@section('title', 'Yeni Defter Reklamı')
@section('page_heading', 'Yeni defter reklamı')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.defter-reklamlari.store') }}" class="admin-card p-6 space-y-4">
        @csrf
        <div class="admin-form-group">
            <label class="admin-label">Başlık</label>
            <input type="text" name="baslik" value="{{ old('baslik') }}" class="admin-input" placeholder="Örn. Firma adı">
            @error('baslik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">İçerik (HTML kullanılabilir)</label>
            <textarea name="icerik" rows="4" class="admin-input" placeholder="Reklam metni veya HTML">{{ old('icerik') }}</textarea>
            @error('icerik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Resim URL</label>
            <input type="text" name="resim" value="{{ old('resim') }}" class="admin-input" placeholder="https://...">
            @error('resim')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Link URL</label>
            <input type="text" name="link" value="{{ old('link') }}" class="admin-input" placeholder="https://...">
            @error('link')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Konum *</label>
            <select name="konum" required class="admin-input">
                <option value="sidebar" {{ old('konum') === 'sidebar' ? 'selected' : '' }}>Sidebar (sağ sütun)</option>
                <option value="ust" {{ old('konum') === 'ust' ? 'selected' : '' }}>Üst alan</option>
                <option value="alt" {{ old('konum') === 'alt' ? 'selected' : '' }}>Alt alan</option>
            </select>
            @error('konum')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600">
            <label for="aktif" class="admin-label mb-0">Aktif (sayfada göster)</label>
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra (küçük önce)</label>
            <input type="number" name="sira" value="{{ old('sira', 0) }}" min="0" class="admin-input">
            @error('sira')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Kaydet</button>
            <a href="{{ route('admin.defter-reklamlari.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
