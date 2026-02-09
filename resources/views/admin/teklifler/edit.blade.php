@extends('layouts.admin')

@section('title', 'Teklif düzenle')
@section('page_heading', 'Teklif düzenle')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <p class="text-sm text-slate-500 mb-4">İhale: {{ $teklif->ihale->from_city }} → {{ $teklif->ihale->to_city }} · Firma: {{ $teklif->company->name }}</p>
        <form method="POST" action="{{ route('admin.teklifler.update', $teklif) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Tutar (₺) *</label>
                <input type="number" name="amount" value="{{ old('amount', $teklif->amount) }}" step="0.01" min="0" required class="admin-input">
                @error('amount')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Mesaj</label>
                <textarea name="message" rows="3" class="admin-input">{{ old('message', $teklif->message) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum *</label>
                <select name="status" required class="admin-input">
                    <option value="pending" {{ old('status', $teklif->status) === 'pending' ? 'selected' : '' }}>Beklemede</option>
                    <option value="accepted" {{ old('status', $teklif->status) === 'accepted' ? 'selected' : '' }}>Kabul</option>
                    <option value="rejected" {{ old('status', $teklif->status) === 'rejected' ? 'selected' : '' }}>Red</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.teklifler.index') }}" class="admin-btn-secondary">İptal</a>
                <form method="POST" action="{{ route('admin.teklifler.destroy', $teklif) }}" class="inline" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">Sil</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
