@extends('layouts.nakliyeci')

@section('title', 'İlanı düzenle')
@section('page_heading', 'İlanı düzenle')
@section('page_subtitle', $pazaryeri->title)

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('nakliyeci.pazaryeri.update', $pazaryeri) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">İlan başlığı *</label>
                <input type="text" name="title" value="{{ old('title', $pazaryeri->title) }}" class="admin-input" placeholder="Örn: 2020 model Mercedes Actros kamyon" required>
                @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Araç tipi *</label>
                    <select name="vehicle_type" class="admin-input" required>
                        @foreach($vehicleTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('vehicle_type', $pazaryeri->vehicle_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">İlan tipi *</label>
                    <select name="listing_type" class="admin-input" required>
                        @foreach($listingTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('listing_type', $pazaryeri->listing_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Fiyat (₺)</label>
                    <input type="number" name="price" value="{{ old('price', $pazaryeri->price) }}" class="admin-input" placeholder="Boş bırakılabilir" min="0" step="0.01">
                    <p class="text-xs text-slate-500 mt-1">Kiralık ilanlarda günlük ücret</p>
                    @error('price')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Model yılı</label>
                    <input type="number" name="year" value="{{ old('year', $pazaryeri->year) }}" class="admin-input" placeholder="Örn: 2020" min="1900" max="{{ date('Y') + 1 }}">
                    @error('year')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Şehir</label>
                <input type="text" name="city" value="{{ old('city', $pazaryeri->city) }}" class="admin-input" placeholder="Örn: İstanbul">
                @error('city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" class="admin-input" rows="4" placeholder="Aracın durumu, özellikleri...">{{ old('description', $pazaryeri->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Kapak fotoğrafı</label>
                @if($pazaryeri->image_path)
                    <p class="text-sm text-slate-500 mb-2">Mevcut: <img src="{{ asset('storage/'.$pazaryeri->image_path) }}" alt="" class="inline-block w-16 h-16 object-cover rounded border border-slate-200 dark:border-slate-600"></p>
                @endif
                <input type="file" name="image_path" accept="image/jpeg,image/png,image/jpg,image/webp" class="admin-input">
                <p class="text-xs text-slate-500 mt-1">Yeni dosya seçerseniz mevcut kapak değişir. JPG, PNG veya WebP. En fazla 5 MB.</p>
                @error('image_path')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Ek fotoğraflar</label>
                @if(count($pazaryeri->gallery_paths) > 1)
                    <p class="text-sm text-slate-500 mb-2">Mevcut {{ count($pazaryeri->gallery_paths) }} fotoğraf var. Aşağıdan eklediğiniz yeniler listenin sonuna eklenir.</p>
                @endif
                <input type="file" name="images[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple class="admin-input">
                <p class="text-xs text-slate-500 mt-1">En fazla 10 fotoğraf. Her biri en fazla 5 MB.</p>
                @error('images')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('images.*')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('nakliyeci.pazaryeri.index') }}" class="admin-btn-secondary">İptal</a>
                <a href="{{ route('pazaryeri.show', [$pazaryeri, \Illuminate\Support\Str::slug($pazaryeri->title)]) }}" target="_blank" rel="noopener" class="text-sm text-slate-500 hover:underline ml-auto">Sitede görüntüle →</a>
            </div>
        </form>
    </div>
</div>
@endsection
