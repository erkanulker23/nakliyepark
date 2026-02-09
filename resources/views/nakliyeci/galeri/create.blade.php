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
                <label class="admin-label">Fotoğraf *</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required class="admin-input">
                <p class="text-xs text-slate-500 mt-1">JPG, PNG veya WebP. En fazla 5 MB.</p>
                @error('image')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama (opsiyonel)</label>
                <input type="text" name="caption" value="{{ old('caption') }}" class="admin-input" placeholder="Örn: 20 m³ kamyon">
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Yükle</button>
                <a href="{{ route('nakliyeci.galeri.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
