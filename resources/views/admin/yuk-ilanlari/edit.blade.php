@extends('layouts.admin')

@section('title', 'Yük ilanı düzenle')
@section('page_heading', 'Yük ilanı düzenle')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.yuk-ilanlari.update', $yuk_ilanlari) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Firma *</label>
                <select name="company_id" required class="admin-input">
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ old('company_id', $yuk_ilanlari->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Nereden (şehir) *</label>
                    <input type="text" name="from_city" value="{{ old('from_city', $yuk_ilanlari->from_city) }}" required class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Nereye (şehir) *</label>
                    <input type="text" name="to_city" value="{{ old('to_city', $yuk_ilanlari->to_city) }}" required class="admin-input">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Yük tipi</label>
                    <input type="text" name="load_type" value="{{ old('load_type', $yuk_ilanlari->load_type) }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Yük tarihi</label>
                    <input type="date" name="load_date" value="{{ old('load_date', $yuk_ilanlari->load_date?->format('Y-m-d')) }}" class="admin-input">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Hacim (m³)</label>
                    <input type="number" name="volume_m3" value="{{ old('volume_m3', $yuk_ilanlari->volume_m3) }}" step="0.01" min="0" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Araç tipi</label>
                    <input type="text" name="vehicle_type" value="{{ old('vehicle_type', $yuk_ilanlari->vehicle_type) }}" class="admin-input">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="3" class="admin-input">{{ old('description', $yuk_ilanlari->description) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum *</label>
                <select name="status" required class="admin-input">
                    <option value="active" {{ old('status', $yuk_ilanlari->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $yuk_ilanlari->status) === 'inactive' ? 'selected' : '' }}>Pasif</option>
                    <option value="draft" {{ old('status', $yuk_ilanlari->status) === 'draft' ? 'selected' : '' }}>Taslak</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.yuk-ilanlari.index') }}" class="admin-btn-secondary">İptal</a>
                <form method="POST" action="{{ route('admin.yuk-ilanlari.destroy', $yuk_ilanlari) }}" class="inline" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">Sil</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
