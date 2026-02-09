@extends('layouts.nakliyeci')

@section('title', 'Deftere yaz')
@section('page_heading', 'Deftere yaz')
@section('page_subtitle', 'Yük veya boş dönüş ilanı ekleyin')

@section('content')
<div class="max-w-xl">
    <div class="admin-card p-6">
        <p class="text-sm text-slate-500 mb-6">Firmanızın boş dönüş veya yük ilanını paylaşın. Günde 200'den fazla ilan ekleniyor.</p>
        <form method="POST" action="{{ route('nakliyeci.ledger.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Nereden (şehir) *</label>
                    <input type="text" name="from_city" value="{{ old('from_city') }}" required class="admin-input" placeholder="Örn. İstanbul">
                    @error('from_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Nereye (şehir) *</label>
                    <input type="text" name="to_city" value="{{ old('to_city') }}" required class="admin-input" placeholder="Örn. Ankara">
                    @error('to_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Yük tarihi</label>
                    <input type="date" name="load_date" value="{{ old('load_date') }}" class="admin-input">
                    @error('load_date')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Hacim (m³)</label>
                    <input type="number" name="volume_m3" value="{{ old('volume_m3') }}" step="0.01" min="0" class="admin-input" placeholder="Örn. 50">
                    @error('volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Yük tipi</label>
                <input type="text" name="load_type" value="{{ old('load_type') }}" class="admin-input" placeholder="Palet, koli, adet vb.">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Araç tipi</label>
                <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" class="admin-input" placeholder="Kamyon, TIR vb.">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="3" class="admin-input" placeholder="Detay varsa yazın">{{ old('description') }}</textarea>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Deftere yaz</button>
                <a href="{{ route('nakliyeci.ledger') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
