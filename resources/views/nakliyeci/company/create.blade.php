@extends('layouts.nakliyeci')

@section('title', 'Firma oluştur')
@section('page_heading', 'Firma Bilgileri')
@section('page_subtitle', 'Firmanızı kaydedin, admin onayından sonra yayına alınacaktır')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <p class="text-sm text-slate-500 mb-6">Formu doldurun; admin onayından sonra teklif verebilecek ve ilanlarınızı yayınlayabileceksiniz.</p>
        <form method="POST" action="{{ route('nakliyeci.company.store') }}" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Firma adı *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="admin-input" placeholder="Firma adınız">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Firma adresiniz</label>
                <input type="text" name="address" value="{{ old('address') }}" class="admin-input" placeholder="Açık adres">
                @error('address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Şehir</label>
                <select name="city" id="province-select" class="admin-input">
                    <option value="">İl seçin</option>
                </select>
                @error('city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İlçe</label>
                <select name="district" id="district-select" class="admin-input" disabled>
                    <option value="">Önce il seçin</option>
                </select>
                @error('district')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div id="map-container" class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 hidden" style="height: 280px;">
                <div id="map" class="w-full h-full"></div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Telefon (+90)</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" inputmode="tel" class="admin-input" placeholder="5XX XXX XX XX">
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Vergi no</label>
                    <input type="text" name="tax_number" value="{{ old('tax_number') }}" inputmode="numeric" class="admin-input">
                    @error('tax_number')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Vergi / Veri dairesi</label>
                    <input type="text" name="tax_office" value="{{ old('tax_office') }}" class="admin-input" placeholder="Örn: Kadıköy VD">
                    @error('tax_office')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İkinci telefon</label>
                <input type="tel" name="phone_2" value="{{ old('phone_2') }}" inputmode="tel" class="admin-input" placeholder="Opsiyonel">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">WhatsApp</label>
                <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" inputmode="tel" class="admin-input" placeholder="5XX XXX XX XX">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">E-posta</label>
                <input type="email" name="email" value="{{ old('email') }}" class="admin-input" placeholder="info@firma.com">
                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Kısa açıklama</label>
                <textarea name="description" rows="3" class="admin-input" placeholder="Firma hakkında kısa bilgi">{{ old('description') }}</textarea>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const provinceSelect = document.getElementById('province-select');
    const districtSelect = document.getElementById('district-select');
    const mapContainer = document.getElementById('map-container');
    let map = null;
    let marker = null;
    let provinces = [];

    provinceSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const id = selected?.value || '';
        const provinceId = selected?.dataset?.id || '';
        const name = selected?.text || '';
        districtSelect.innerHTML = '<option value="">İlçe seçin</option>';
        districtSelect.disabled = !id;

        if (!id) {
            mapContainer.classList.add('hidden');
            if (marker && map) map.removeLayer(marker);
            marker = null;
            return;
        }

        const prov = provinces.find(p => p.name === id);
        if (prov && prov.latitude != null && prov.longitude != null) {
            mapContainer.classList.remove('hidden');
            if (!map) {
                map = L.map('map').setView([prov.latitude, prov.longitude], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
            } else {
                map.setView([prov.latitude, prov.longitude], 10);
            }
            if (marker) map.removeLayer(marker);
            marker = L.marker([prov.latitude, prov.longitude]).addTo(map).bindPopup(name);
        }

        fetch('{{ route("api.turkey.districts") }}?province_id=' + (provinceId || (prov && prov.id) || ''))
            .then(r => r.json())
            .then(data => {
                if (!data.data) return;
                data.data.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.name;
                    opt.textContent = d.name;
                    districtSelect.appendChild(opt);
                });
                const oldDistrict = '{{ old("district") }}';
                if (oldDistrict) districtSelect.value = oldDistrict;
            })
            .catch(() => {});
    });

    fetch('{{ route("api.turkey.provinces") }}')
        .then(r => r.json())
        .then(data => {
            if (!data.data) return;
            provinces = data.data;
            data.data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.lat = p.latitude;
                opt.dataset.lng = p.longitude;
                opt.dataset.id = p.id;
                provinceSelect.appendChild(opt);
            });
            const oldCity = '{{ old("city") }}';
            if (oldCity) {
                provinceSelect.value = oldCity;
                provinceSelect.dispatchEvent(new Event('change'));
            }
        })
        .catch(() => {});
})();
</script>
@endpush
@endsection
