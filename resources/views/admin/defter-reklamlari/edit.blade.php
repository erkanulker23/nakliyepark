@extends('layouts.admin')

@section('title', 'Defter reklamı düzenle')
@section('page_heading', 'Defter reklamı düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.defter-reklamlari.update', ['defter_reklamlari' => $defter_reklami]) }}" class="admin-card p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
            <label class="admin-label">Başlık</label>
            <input type="text" name="baslik" value="{{ old('baslik', $defter_reklami->baslik) }}" class="admin-input">
            @error('baslik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">İçerik (HTML kullanılabilir)</label>
            <textarea name="icerik" rows="4" class="admin-input">{{ old('icerik', $defter_reklami->icerik) }}</textarea>
            @error('icerik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Resim URL</label>
            <input type="text" name="resim" value="{{ old('resim', $defter_reklami->resim) }}" class="admin-input">
            @error('resim')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Link URL</label>
            <input type="text" name="link" value="{{ old('link', $defter_reklami->link) }}" class="admin-input">
            @error('link')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Konum *</label>
            <select name="konum" required class="admin-input">
                <option value="sidebar" {{ old('konum', $defter_reklami->konum) === 'sidebar' ? 'selected' : '' }}>Sidebar (sağ sütun)</option>
                <option value="ust" {{ old('konum', $defter_reklami->konum) === 'ust' ? 'selected' : '' }}>Üst alan</option>
                <option value="alt" {{ old('konum', $defter_reklami->konum) === 'alt' ? 'selected' : '' }}>Alt alan</option>
            </select>
            @error('konum')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $defter_reklami->aktif) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600">
            <label for="aktif" class="admin-label mb-0">Aktif (sayfada göster)</label>
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra</label>
            <input type="number" name="sira" value="{{ old('sira', $defter_reklami->sira) }}" min="0" class="admin-input">
            @error('sira')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Güncelle</button>
            <a href="{{ route('admin.defter-reklamlari.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.defter-reklamlari.destroy', ['defter_reklamlari' => $defter_reklami]) }}" class="inline mt-3" onsubmit="return confirm('Bu reklamı silmek istediğinize emin misiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn-danger">Sil</button>
    </form>
</div>
@endsection
