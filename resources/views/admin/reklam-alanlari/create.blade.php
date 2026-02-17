@extends('layouts.admin')

@section('title', 'Yeni Reklam Alanı')
@section('page_heading', 'Yeni reklam alanı')
@section('page_subtitle', 'Google AdSense kodu veya görsel reklam ekleyin; sayfa ve konum seçin.')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.reklam-alanlari.store') }}" class="admin-card p-6 space-y-4" id="adZoneForm">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="admin-form-group">
                <label class="admin-label">Sayfa *</label>
                <select name="sayfa" required class="admin-input">
                    @foreach($sayfaSecenekleri ?? \App\Models\AdZone::sayfaSecenekleri() as $key => $label)
                        <option value="{{ $key }}" {{ old('sayfa') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('sayfa')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Konum *</label>
                <select name="konum" required class="admin-input">
                    @foreach($konumSecenekleri ?? \App\Models\AdZone::konumSecenekleri() as $key => $label)
                        <option value="{{ $key }}" {{ old('konum') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('konum')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Başlık (sadece admin listesinde görünür)</label>
            <input type="text" name="baslik" value="{{ old('baslik') }}" class="admin-input" placeholder="Örn. Blog sidebar AdSense">
            @error('baslik')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Reklam tipi *</label>
            <select name="tip" required class="admin-input" id="adTip">
                <option value="image" {{ old('tip', 'image') === 'image' ? 'selected' : '' }}>Görsel reklam (kendi resminiz)</option>
                <option value="code" {{ old('tip') === 'code' ? 'selected' : '' }}>Kod (Google AdSense / HTML)</option>
            </select>
            @error('tip')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Görsel alanları --}}
        <div id="adImageFields" class="space-y-4">
            <div class="admin-form-group">
                <label class="admin-label">Reklam görseli URL</label>
                <input type="text" name="resim" value="{{ old('resim') }}" class="admin-input" placeholder="https://... veya /storage/...">
                @error('resim')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Tıklanınca gidilecek link</label>
                <input type="text" name="link" value="{{ old('link') }}" class="admin-input" placeholder="https://...">
                @error('link')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Kod alanı --}}
        <div id="adCodeFields" class="admin-form-group hidden">
            <label class="admin-label">Reklam kodu (AdSense / HTML)</label>
            <textarea name="kod" rows="8" class="admin-input font-mono text-sm" placeholder="Google AdSense veya herhangi bir reklam script/HTML kodunu buraya yapıştırın.">{{ old('kod') }}</textarea>
            @error('kod')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="admin-form-group flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600">
            <label for="aktif" class="admin-label mb-0">Aktif (sayfada göster)</label>
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra (küçük önce)</label>
            <input type="number" name="sira" value="{{ old('sira', 0) }}" min="0" class="admin-input w-24">
            @error('sira')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Kaydet</button>
            <a href="{{ route('admin.reklam-alanlari.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tip = document.getElementById('adTip');
    var imageFields = document.getElementById('adImageFields');
    var codeFields = document.getElementById('adCodeFields');
    function toggle() {
        var isCode = tip.value === 'code';
        imageFields.classList.toggle('hidden', isCode);
        codeFields.classList.toggle('hidden', !isCode);
    }
    tip.addEventListener('change', toggle);
    toggle();
});
</script>
@endsection
