@extends('layouts.admin')

@section('title', 'İhale düzenle')
@section('page_heading', 'İhale düzenle')
@section('page_subtitle', $ihale->from_city . ' → ' . $ihale->to_city)

@section('content')
<div class="max-w-4xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.ihaleler.update', $ihale) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Üye (boş = misafir)</label>
                <select name="user_id" class="admin-input">
                    <option value="">— Misafir —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id', $ihale->user_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Hizmet tipi</label>
                    <select name="service_type" class="admin-input">
                        @foreach(\App\Models\Ihale::serviceTypeLabels() as $value => $label)
                            <option value="{{ $value }}" {{ old('service_type', $ihale->service_type ?? 'evden_eve_nakliyat') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Oda / büyüklük (örn. 3+1)</label>
                    <input type="text" name="room_type" value="{{ old('room_type', $ihale->room_type) }}" class="admin-input" placeholder="Opsiyonel">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Misafir adı</label>
                    <input type="text" name="guest_contact_name" value="{{ old('guest_contact_name', $ihale->guest_contact_name) }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Misafir e-posta</label>
                    <input type="email" name="guest_contact_email" value="{{ old('guest_contact_email', $ihale->guest_contact_email) }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Misafir telefon</label>
                    <input type="text" name="guest_contact_phone" value="{{ old('guest_contact_phone', $ihale->guest_contact_phone) }}" class="admin-input">
                </div>
            </div>
            <div class="border-t border-slate-200 pt-5 mt-6">
                <h4 class="font-semibold text-slate-800 mb-3">Nereden</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Şehir *</label>
                        <input type="text" name="from_city" value="{{ old('from_city', $ihale->from_city) }}" required class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Adres</label>
                        <input type="text" name="from_address" value="{{ old('from_address', $ihale->from_address) }}" class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İlçe</label>
                        <input type="text" name="from_district" value="{{ old('from_district', $ihale->from_district) }}" class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Mahalle</label>
                        <input type="text" name="from_neighborhood" value="{{ old('from_neighborhood', $ihale->from_neighborhood) }}" class="admin-input">
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 mb-3">Nereye</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Şehir *</label>
                        <input type="text" name="to_city" value="{{ old('to_city', $ihale->to_city) }}" required class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Adres</label>
                        <input type="text" name="to_address" value="{{ old('to_address', $ihale->to_address) }}" class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İlçe</label>
                        <input type="text" name="to_district" value="{{ old('to_district', $ihale->to_district) }}" class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Mahalle</label>
                        <input type="text" name="to_neighborhood" value="{{ old('to_neighborhood', $ihale->to_neighborhood) }}" class="admin-input">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="admin-form-group">
                    <label class="admin-label">Mesafe (km)</label>
                    <input type="number" name="distance_km" value="{{ old('distance_km', $ihale->distance_km) }}" step="0.01" min="0" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Taşınma tarihi (başlangıç)</label>
                    <input type="date" name="move_date" value="{{ old('move_date', $ihale->move_date?->format('Y-m-d')) }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Taşınma tarihi (bitiş)</label>
                    <input type="date" name="move_date_end" value="{{ old('move_date_end', $ihale->move_date_end?->format('Y-m-d')) }}" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Hacim (m³)</label>
                    <input type="number" name="volume_m3" value="{{ old('volume_m3', $ihale->volume_m3) }}" step="0.01" min="0" class="admin-input">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="4" class="admin-input">{{ old('description', $ihale->description) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum *</label>
                <select name="status" required class="admin-input">
                    <option value="pending" {{ old('status', $ihale->status) === 'pending' ? 'selected' : '' }}>Onay bekliyor</option>
                    <option value="draft" {{ old('status', $ihale->status) === 'draft' ? 'selected' : '' }}>Taslak</option>
                    <option value="published" {{ old('status', $ihale->status) === 'published' ? 'selected' : '' }}>Yayında</option>
                    <option value="closed" {{ old('status', $ihale->status) === 'closed' ? 'selected' : '' }}>Kapalı</option>
                    <option value="cancelled" {{ old('status', $ihale->status) === 'cancelled' ? 'selected' : '' }}>İptal</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.ihaleler.show', $ihale) }}" class="admin-btn-secondary">İptal</a>
                <form method="POST" action="{{ route('admin.ihaleler.destroy', $ihale) }}" class="inline" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">İhaleyi sil</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
