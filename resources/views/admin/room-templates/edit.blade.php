@extends('layouts.admin')

@section('title', 'Oda şablonu düzenle')
@section('page_heading', 'Oda şablonu düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.room-templates.update', $roomTemplate) }}" class="admin-card p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
            <label class="admin-label">Ad *</label>
            <input type="text" name="name" value="{{ old('name', $roomTemplate->name) }}" required class="admin-input">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Varsayılan hacim (m³) *</label>
            <input type="number" name="default_volume_m3" value="{{ old('default_volume_m3', $roomTemplate->default_volume_m3) }}" step="0.01" min="0" required class="admin-input">
            @error('default_volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $roomTemplate->sort_order) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Güncelle</button>
            <a href="{{ route('admin.room-templates.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.room-templates.destroy', $roomTemplate) }}" class="inline mt-3" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn-danger">Sil</button>
    </form>
</div>
@endsection
