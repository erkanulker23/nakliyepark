@extends('layouts.admin')

@section('title', 'Yeni Defter Reklamı')
@section('page_heading', 'Yeni defter reklamı')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.defter-reklamlari.store') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Başlık</label>
            <input type="text" name="baslik" value="{{ old('baslik') }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="Örn. Firma adı">
            @error('baslik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">İçerik (HTML kullanılabilir)</label>
            <textarea name="icerik" rows="4" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="Reklam metni veya HTML">{{ old('icerik') }}</textarea>
            @error('icerik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Resim URL</label>
            <input type="text" name="resim" value="{{ old('resim') }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="https://...">
            @error('resim')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Link URL</label>
            <input type="text" name="link" value="{{ old('link') }}" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="https://...">
            @error('link')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konum *</label>
            <select name="konum" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                <option value="sidebar" {{ old('konum') === 'sidebar' ? 'selected' : '' }}>Sidebar (sağ sütun)</option>
                <option value="ust" {{ old('konum') === 'ust' ? 'selected' : '' }}>Üst alan</option>
                <option value="alt" {{ old('konum') === 'alt' ? 'selected' : '' }}>Alt alan</option>
            </select>
            @error('konum')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="aktif" class="text-sm text-slate-700 dark:text-slate-300">Aktif (sayfada göster)</label>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sıra (küçük önce)</label>
            <input type="number" name="sira" value="{{ old('sira', 0) }}" min="0" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('sira')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Kaydet</button>
            <a href="{{ route('admin.defter-reklamlari.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500">İptal</a>
        </div>
    </form>
</div>
@endsection
