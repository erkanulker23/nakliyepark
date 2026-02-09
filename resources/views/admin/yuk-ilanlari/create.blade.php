@extends('layouts.admin')

@section('title', 'Yeni yük ilanı')
@section('page_heading', 'Yeni yük ilanı')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.yuk-ilanlari.store') }}" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Firma *</label>
                <select name="company_id" required class="admin-input">
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ old('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Nereden (şehir) *</label>
                    <input type="text" name="from_city" value="{{ old('from_city') }}" required class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Nereye (şehir) *</label>
                    <input type="text" name="to_city" value="{{ old('to_city') }}" required class="admin-input">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Yük tipi</label>
                    <input type="text" name="load_type" value="{{ old('load_type') }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Yük tarihi</label>
                    <input type="date" name="load_date" value="{{ old('load_date') }}" class="admin-input">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Hacim (m³)</label>
                    <input type="number" name="volume_m3" value="{{ old('volume_m3') }}" step="0.01" min="0" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Araç tipi</label>
                    <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" class="admin-input">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="3" class="admin-input">{{ old('description') }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum *</label>
                <select name="status" required class="admin-input">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive">Pasif</option>
                    <option value="draft">Taslak</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.yuk-ilanlari.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
