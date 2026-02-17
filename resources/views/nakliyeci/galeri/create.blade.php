@extends('layouts.nakliyeci')

@section('title', 'Fotoğraf ekle')
@section('page_heading', 'Galeriye fotoğraf ekle')
@section('page_subtitle', 'Araç veya iş fotoğrafı')

@section('content')
<div class="max-w-xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('nakliyeci.galeri.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Fotoğraflar (toplu seçim) *</label>
                <input type="file" name="images[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple class="admin-input">
                <p class="text-xs text-slate-500 mt-1">Birden fazla fotoğraf seçebilirsiniz. JPG, PNG veya WebP. Dosya başı en fazla 5 MB.</p>
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">«413 Request Entity Too Large» hatası alırsanız sunucuda yükleme limiti düşüktür; proje kökündeki <code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">nginx-upload-size.conf.example</code> dosyasına bakın.</p>
                @error('images')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('images.*')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Ortak açıklama (opsiyonel)</label>
                <input type="text" name="caption" value="{{ old('caption') }}" class="admin-input" placeholder="Örn: Araç filosu – tüm fotoğraflar için geçerli">
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Toplu yükle</button>
                <a href="{{ route('nakliyeci.galeri.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
