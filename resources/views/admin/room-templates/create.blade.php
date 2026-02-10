@extends('layouts.admin')

@section('title', 'Yeni oda şablonu')
@section('page_heading', 'Yeni oda şablonu')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.room-templates.store') }}" class="admin-card p-6 space-y-4">
        @csrf
        <div class="admin-form-group">
            <label class="admin-label">Ad *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="admin-input" placeholder="Örn: Salon">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Varsayılan hacim (m³) *</label>
            <input type="number" name="default_volume_m3" value="{{ old('default_volume_m3', '0') }}" step="0.01" min="0" required class="admin-input">
            @error('default_volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-label">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="admin-input">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="admin-btn-primary">Kaydet</button>
            <a href="{{ route('admin.room-templates.index') }}" class="admin-btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
