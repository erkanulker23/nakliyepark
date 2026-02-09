@extends('layouts.admin')

@section('title', 'Defter reklamı düzenle')
@section('page_heading', 'Defter reklamı düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.defter-reklamlari.update', $defter_reklami) }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Başlık</label>
            <input type="text" name="baslik" value="{{ old('baslik', $defter_reklami->baslik) }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('baslik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">İçerik (HTML kullanılabilir)</label>
            <textarea name="icerik" rows="4" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">{{ old('icerik', $defter_reklami->icerik) }}</textarea>
            @error('icerik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Resim URL</label>
            <input type="text" name="resim" value="{{ old('resim', $defter_reklami->resim) }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('resim')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Link URL</label>
            <input type="text" name="link" value="{{ old('link', $defter_reklami->link) }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('link')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konum *</label>
            <select name="konum" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                <option value="sidebar" {{ old('konum', $defter_reklami->konum) === 'sidebar' ? 'selected' : '' }}>Sidebar (sağ sütun)</option>
                <option value="ust" {{ old('konum', $defter_reklami->konum) === 'ust' ? 'selected' : '' }}>Üst alan</option>
                <option value="alt" {{ old('konum', $defter_reklami->konum) === 'alt' ? 'selected' : '' }}>Alt alan</option>
            </select>
            @error('konum')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $defter_reklami->aktif) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="aktif" class="text-sm text-slate-700 dark:text-slate-300">Aktif (sayfada göster)</label>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sıra</label>
            <input type="number" name="sira" value="{{ old('sira', $defter_reklami->sira) }}" min="0" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('sira')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Güncelle</button>
            <a href="{{ route('admin.defter-reklamlari.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500">İptal</a>
            <form method="POST" action="{{ route('admin.defter-reklamlari.destroy', $defter_reklami) }}" class="inline ml-auto" onsubmit="return confirm('Bu reklamı silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">Sil</button>
            </form>
        </div>
    </form>
</div>
@endsection
