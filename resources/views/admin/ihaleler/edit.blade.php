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
                <h4 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Nereden (il / ilçe / mahalle API)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-label">İl *</label>
                        <select id="from_province_id" class="admin-input" required>
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İlçe</label>
                        <select id="from_district_id" class="admin-input">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Mahalle</label>
                        <select id="from_neighborhood_id" class="admin-input">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group sm:col-span-2">
                        <label class="admin-label">Adres</label>
                        <input type="text" name="from_address" value="{{ old('from_address', $ihale->from_address) }}" class="admin-input">
                    </div>
                </div>
                <input type="hidden" name="from_city" id="from_city" value="{{ old('from_city', $ihale->from_city) }}">
                <input type="hidden" name="from_district" id="from_district" value="{{ old('from_district', $ihale->from_district) }}">
                <input type="hidden" name="from_neighborhood" id="from_neighborhood" value="{{ old('from_neighborhood', $ihale->from_neighborhood) }}">
            </div>
            <div class="border-t border-slate-200 dark:border-slate-600 pt-5">
                <h4 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Nereye (il / ilçe / mahalle API)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-label">İl *</label>
                        <select id="to_province_id" class="admin-input" required>
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İlçe</label>
                        <select id="to_district_id" class="admin-input">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Mahalle</label>
                        <select id="to_neighborhood_id" class="admin-input">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div class="admin-form-group sm:col-span-2">
                        <label class="admin-label">Adres</label>
                        <input type="text" name="to_address" value="{{ old('to_address', $ihale->to_address) }}" class="admin-input">
                    </div>
                </div>
                <input type="hidden" name="to_city" id="to_city" value="{{ old('to_city', $ihale->to_city) }}">
                <input type="hidden" name="to_district" id="to_district" value="{{ old('to_district', $ihale->to_district) }}">
                <input type="hidden" name="to_neighborhood" id="to_neighborhood" value="{{ old('to_neighborhood', $ihale->to_neighborhood) }}">
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
            </div>
        </form>
        <form method="POST" action="{{ route('admin.ihaleler.destroy', $ihale) }}" class="inline mt-3" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger">İhaleyi sil</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var apiBase = '{{ url("/api/turkey") }}';
    var fromCity = {{ json_encode(old('from_city', $ihale->from_city)) }};
    var fromDistrict = {{ json_encode(old('from_district', $ihale->from_district)) }};
    var fromNeighborhood = {{ json_encode(old('from_neighborhood', $ihale->from_neighborhood)) }};
    var toCity = {{ json_encode(old('to_city', $ihale->to_city)) }};
    var toDistrict = {{ json_encode(old('to_district', $ihale->to_district)) }};
    var toNeighborhood = {{ json_encode(old('to_neighborhood', $ihale->to_neighborhood)) }};
    var fromDistricts = [], toDistricts = [];

    function fillProvinces() {
        fetch(apiBase + '/provinces').then(function(r) { return r.json(); }).then(function(res) {
            if (!res.data || !res.data.length) return;
            var fromSel = document.getElementById('from_province_id');
            var toSel = document.getElementById('to_province_id');
            res.data.forEach(function(p) {
                fromSel.appendChild(new Option(p.name, p.id));
                toSel.appendChild(new Option(p.name, p.id));
            });
            selectOptionByText(fromSel, fromCity);
            selectOptionByText(toSel, toCity);
            if (fromSel.value) loadDistricts('from', fromSel.value);
            if (toSel.value) loadDistricts('to', toSel.value);
        });
    }
    function selectOptionByText(sel, text) {
        if (!text) return;
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text === text) { sel.selectedIndex = i; return; }
        }
    }
    function loadDistricts(side, provinceId) {
        if (!provinceId) return;
        fetch(apiBase + '/districts?province_id=' + provinceId).then(function(r) { return r.json(); }).then(function(res) {
            if (!res.data) return;
            var store = side === 'from' ? fromDistricts : toDistricts;
            store.length = 0;
            res.data.forEach(function(d) { store.push(d); });
            var sel = document.getElementById(side + '_district_id');
            sel.innerHTML = '<option value="">Önce il seçin</option>';
            res.data.forEach(function(d) { sel.appendChild(new Option(d.name, d.id)); });
            selectOptionByText(sel, side === 'from' ? fromDistrict : toDistrict);
            var distId = sel.value;
            if (distId) fillNeighborhoods(side, distId);
            var neighSel = document.getElementById(side + '_neighborhood_id');
            if (!distId) neighSel.innerHTML = '<option value="">Önce ilçe seçin</option>';
        });
    }
    function fillNeighborhoods(side, districtId) {
        var store = side === 'from' ? fromDistricts : toDistricts;
        var district = store.find(function(d) { return d.id == districtId; });
        var neighSel = document.getElementById(side + '_neighborhood_id');
        neighSel.innerHTML = '<option value="">Önce ilçe seçin</option>';
        if (!district || !district.neighborhoods || !district.neighborhoods.length) return;
        district.neighborhoods.forEach(function(n) { neighSel.appendChild(new Option(n.name, n.id)); });
        selectOptionByText(neighSel, side === 'from' ? fromNeighborhood : toNeighborhood);
    }

    var fromProv = document.getElementById('from_province_id');
    var toProv = document.getElementById('to_province_id');
    var fromDist = document.getElementById('from_district_id');
    var toDist = document.getElementById('to_district_id');
    var fromNeigh = document.getElementById('from_neighborhood_id');
    var toNeigh = document.getElementById('to_neighborhood_id');

    fromProv.addEventListener('change', function() {
        document.getElementById('from_city').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        document.getElementById('from_district').value = '';
        document.getElementById('from_neighborhood').value = '';
        fromDist.innerHTML = '<option value="">Önce il seçin</option>';
        fromNeigh.innerHTML = '<option value="">Önce ilçe seçin</option>';
        if (this.value) loadDistricts('from', this.value);
    });
    toProv.addEventListener('change', function() {
        document.getElementById('to_city').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        document.getElementById('to_district').value = '';
        document.getElementById('to_neighborhood').value = '';
        toDist.innerHTML = '<option value="">Önce il seçin</option>';
        toNeigh.innerHTML = '<option value="">Önce ilçe seçin</option>';
        if (this.value) loadDistricts('to', this.value);
    });
    fromDist.addEventListener('change', function() {
        document.getElementById('from_district').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        document.getElementById('from_neighborhood').value = '';
        fromNeigh.innerHTML = '<option value="">Önce ilçe seçin</option>';
        if (this.value) fillNeighborhoods('from', this.value);
    });
    toDist.addEventListener('change', function() {
        document.getElementById('to_district').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        document.getElementById('to_neighborhood').value = '';
        toNeigh.innerHTML = '<option value="">Önce ilçe seçin</option>';
        if (this.value) fillNeighborhoods('to', this.value);
    });
    fromNeigh.addEventListener('change', function() {
        document.getElementById('from_neighborhood').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
    });
    toNeigh.addEventListener('change', function() {
        document.getElementById('to_neighborhood').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
    });

    fillProvinces();
})();
</script>
@endpush
@endsection
