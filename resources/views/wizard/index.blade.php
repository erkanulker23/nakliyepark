@extends('layouts.app')

@section('title', 'Nakliye İhalesi - NakliyePark')
@section('meta_description', 'Nakliye ihalesi adımları: Rota, hacim, detay ve fotoğraf. NakliyePark ile ihale oluşturup firmalardan teklif alın.')

@section('content')
<div class="px-4 py-6 max-w-lg mx-auto">
    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Nakliye İhalesi Oluştur</h1>

    {{-- Stepper - mobile first --}}
    <div class="flex justify-between mb-6">
        @foreach([1 => 'Rota', 2 => 'Hacim', 3 => 'Detay', 4 => 'Fotoğraf'] as $s => $label)
            <div class="flex flex-col items-center">
                <span class="stepper-dot {{ $step >= $s ? ($step > $s ? 'stepper-dot-done' : 'stepper-dot-active') : 'stepper-dot-pending' }}"></span>
                <span class="text-xs mt-1 text-slate-500 {{ $step >= $s ? 'font-medium text-slate-700' : '' }}">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <form id="wizard-form" action="{{ route('wizard.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Step 1: Nereden Nereye (İl, İlçe, Mahalle API'den) --}}
        <div data-step="1" class="step-content {{ $step !== 1 ? 'hidden' : '' }}">
            <div class="space-y-4">
                <p class="text-sm text-slate-500 mb-2">Tüm il, ilçe ve mahalleler Türkiye adres API'sinden yüklenir.</p>
                {{-- Nereden --}}
                <div class="grid grid-cols-1 gap-3">
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 col-span-full">Nereden *</label>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">İl</label>
                        <select name="from_province_id" id="from_province_id" required
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">İlçe</label>
                        <select name="from_district_id" id="from_district_id"
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">Mahalle</label>
                        <select name="from_neighborhood_id" id="from_neighborhood_id"
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">Sokak / adres detayı (opsiyonel)</label>
                        <input type="text" name="from_address" value="{{ old('from_address') }}"
                               class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl" placeholder="Sokak, bina no vb.">
                    </div>
                </div>
                <input type="hidden" name="from_city" id="from_city" value="{{ old('from_city') }}">
                <input type="hidden" name="from_district" id="from_district" value="{{ old('from_district') }}">
                <input type="hidden" name="from_neighborhood" id="from_neighborhood" value="{{ old('from_neighborhood') }}">

                {{-- Nereye --}}
                <div class="grid grid-cols-1 gap-3">
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 col-span-full">Nereye *</label>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">İl</label>
                        <select name="to_province_id" id="to_province_id" required
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">İlçe</label>
                        <select name="to_district_id" id="to_district_id"
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">Mahalle</label>
                        <select name="to_neighborhood_id" id="to_neighborhood_id"
                                class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-0.5">Sokak / adres detayı (opsiyonel)</label>
                        <input type="text" name="to_address" value="{{ old('to_address') }}"
                               class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl" placeholder="Sokak, bina no vb.">
                    </div>
                </div>
                <input type="hidden" name="to_city" id="to_city" value="{{ old('to_city') }}">
                <input type="hidden" name="to_district" id="to_district" value="{{ old('to_district') }}">
                <input type="hidden" name="to_neighborhood" id="to_neighborhood" value="{{ old('to_neighborhood') }}">

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Tahmini mesafe (km)</label>
                    <input type="number" name="distance_km" inputmode="decimal" min="0" step="0.1" value="{{ old('distance_km') }}"
                           class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                </div>
            </div>
        </div>

        {{-- Step 2: Hacim (Kayıtlı odalar) --}}
        <div data-step="2" class="step-content {{ $step !== 2 ? 'hidden' : '' }}">
            <p class="text-sm text-slate-500 mb-4">Oda türlerine göre hacim ekleyin veya toplam girin.</p>
            <div class="space-y-3">
                @foreach($rooms as $room)
                    <div class="flex items-center justify-between card-touch">
                        <span>{{ $room->name }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-touch w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 vol-minus" data-m3="{{ $room->default_volume_m3 }}">−</button>
                            <span class="vol-display min-w-[3rem] text-center" data-default="{{ $room->default_volume_m3 }}">0</span> m³
                            <button type="button" class="btn-touch w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-900/50 vol-plus" data-m3="{{ $room->default_volume_m3 }}">+</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-between card-touch bg-sky-50 dark:bg-sky-900/20">
                <span class="font-semibold">Toplam hacim</span>
                <span id="total-volume">0</span> m³
            </div>
            <input type="hidden" name="volume_m3" id="volume_m3" value="{{ old('volume_m3', 0) }}">
        </div>

        {{-- Step 3: Tarih & detay --}}
        <div data-step="3" class="step-content {{ $step !== 3 ? 'hidden' : '' }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Taşınma tarihi</label>
                    <input type="date" name="move_date" value="{{ old('move_date') }}"
                           class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Ek not / eşya bilgisi</label>
                    <textarea name="description" rows="4" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl"
                              placeholder="Örn: Buzdolabı, koltuk takımı...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Step 4: Fotoğraf --}}
        <div data-step="4" class="step-content {{ $step !== 4 ? 'hidden' : '' }}">
            <p class="text-sm text-slate-500 mb-4">Eşyalarınızın fotoğrafını yükleyin (isteğe bağlı). Mobilde kamera ile çekebilirsiniz.</p>
            <label class="block">
                <span class="btn-touch w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl flex flex-col items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Fotoğraf seç veya çek</span>
                </span>
                <input type="file" name="photos[]" accept="image/*" capture="environment" multiple class="hidden" id="photos">
            </label>
            <div id="photo-preview" class="mt-3 flex flex-wrap gap-2"></div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="button" id="prev-btn" class="btn-touch flex-1 bg-slate-200 dark:bg-slate-700 rounded-xl hidden">Geri</button>
            <button type="button" id="next-btn" class="btn-touch flex-1 bg-sky-500 text-white rounded-xl">İleri</button>
            <button type="submit" id="submit-btn" class="btn-touch flex-1 bg-emerald-500 text-white rounded-xl hidden">Gönder</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    const form = document.getElementById('wizard-form');
    const steps = form.querySelectorAll('.step-content');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const totalVolumeEl = document.getElementById('total-volume');
    const volumeInput = document.getElementById('volume_m3');
    let currentStep = 1;

    const apiBase = '{{ url("/api/turkey") }}';
    let fromDistricts = [];
    let toDistricts = [];

    function fillProvinces() {
        fetch(apiBase + '/provinces')
            .then(r => r.json())
            .then(res => {
                if (!res.data || !res.data.length) return;
                const fromSel = document.getElementById('from_province_id');
                const toSel = document.getElementById('to_province_id');
                res.data.forEach(p => {
                    fromSel.appendChild(new Option(p.name, p.id));
                    toSel.appendChild(new Option(p.name, p.id));
                });
            })
            .catch(() => {});
    }

    function fillDistricts(provinceId, targetSelect, storeKey) {
        const sel = document.getElementById(targetSelect);
        sel.innerHTML = '<option value="">Yükleniyor...</option>';
        if (!provinceId) {
            sel.innerHTML = '<option value="">Önce il seçin</option>';
            return;
        }
        fetch(apiBase + '/districts?province_id=' + provinceId)
            .then(r => r.json())
            .then(res => {
                if (!res.data) { sel.innerHTML = '<option value="">İlçe yok</option>'; return; }
                if (storeKey === 'from') fromDistricts = res.data;
                else toDistricts = res.data;
                sel.innerHTML = '<option value="">İlçe seçin</option>';
                res.data.forEach(d => {
                    sel.appendChild(new Option(d.name, d.id));
                });
            })
            .catch(() => { sel.innerHTML = '<option value="">Yüklenemedi</option>'; });
    }

    function fillNeighborhoods(districts, districtId, targetSelect) {
        const sel = document.getElementById(targetSelect);
        sel.innerHTML = '<option value="">Mahalle seçin</option>';
        if (!districtId || !districts.length) return;
        const district = districts.find(d => d.id == districtId);
        if (!district || !district.neighborhoods || !district.neighborhoods.length) return;
        district.neighborhoods.forEach(n => {
            sel.appendChild(new Option(n.name, n.id));
        });
    }

    function bindLocationHandlers() {
        const fromProvince = document.getElementById('from_province_id');
        const toProvince = document.getElementById('to_province_id');
        const fromDistrict = document.getElementById('from_district_id');
        const toDistrict = document.getElementById('to_district_id');
        const fromNeighborhood = document.getElementById('from_neighborhood_id');
        const toNeighborhood = document.getElementById('to_neighborhood_id');

        fromProvince.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('from_city').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
            document.getElementById('from_district').value = '';
            document.getElementById('from_neighborhood').value = '';
            fromDistrict.innerHTML = '<option value="">Önce il seçin</option>';
            fromNeighborhood.innerHTML = '<option value="">Önce ilçe seçin</option>';
            if (id) fillDistricts(id, 'from_district_id', 'from');
        });
        toProvince.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('to_city').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
            document.getElementById('to_district').value = '';
            document.getElementById('to_neighborhood').value = '';
            toDistrict.innerHTML = '<option value="">Önce il seçin</option>';
            toNeighborhood.innerHTML = '<option value="">Önce ilçe seçin</option>';
            if (id) fillDistricts(id, 'to_district_id', 'to');
        });

        fromDistrict.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('from_district').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
            document.getElementById('from_neighborhood').value = '';
            fillNeighborhoods(fromDistricts, id, 'from_neighborhood_id');
        });
        toDistrict.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('to_district').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
            document.getElementById('to_neighborhood').value = '';
            fillNeighborhoods(toDistricts, id, 'to_neighborhood_id');
        });

        fromNeighborhood.addEventListener('change', function() {
            document.getElementById('from_neighborhood').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        });
        toNeighborhood.addEventListener('change', function() {
            document.getElementById('to_neighborhood').value = this.selectedIndex ? this.options[this.selectedIndex].text : '';
        });
    }

    fillProvinces();
    bindLocationHandlers();

    function updateStep() {
        steps.forEach((el, i) => el.classList.toggle('hidden', i + 1 !== currentStep));
        prevBtn.classList.toggle('hidden', currentStep <= 1);
        nextBtn.classList.toggle('hidden', currentStep >= 4);
        submitBtn.classList.toggle('hidden', currentStep !== 4);
    }

    prevBtn.addEventListener('click', () => { currentStep = Math.max(1, currentStep - 1); updateStep(); });
    nextBtn.addEventListener('click', () => {
        if (currentStep === 1) {
            const fromCity = document.getElementById('from_city');
            const toCity = document.getElementById('to_city');
            if (!fromCity.value.trim() || !toCity.value.trim()) { alert('Nereden ve nereye için il seçin.'); return; }
        }
        if (currentStep === 2 && parseFloat(volumeInput.value) <= 0) {
            alert('En az bir oda için hacim ekleyin veya toplam hacim girin.'); return;
        }
        currentStep = Math.min(4, currentStep + 1);
        updateStep();
    });

    // Volume calculator
    let totalVol = 0;
    const volDisplays = form.querySelectorAll('.vol-display');
    form.querySelectorAll('.vol-plus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const m3 = parseFloat(btn.dataset.m3);
            const disp = volDisplays[i];
            const count = parseInt(disp.textContent || '0') + 1;
            disp.textContent = count;
            totalVol += m3;
            totalVolumeEl.textContent = totalVol.toFixed(1);
            volumeInput.value = totalVol.toFixed(2);
        });
    });
    form.querySelectorAll('.vol-minus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const disp = volDisplays[i];
            const count = Math.max(0, parseInt(disp.textContent || '0') - 1);
            const m3 = parseFloat(btn.dataset.m3);
            if (count < parseInt(disp.textContent || '0')) {
                totalVol = Math.max(0, totalVol - m3);
                totalVolumeEl.textContent = totalVol.toFixed(1);
                volumeInput.value = totalVol.toFixed(2);
            }
            disp.textContent = count;
        });
    });

    // Photo preview
    document.getElementById('photos').addEventListener('change', function(e) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '';
        Array.from(this.files || []).slice(0, 6).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-20 h-20 object-cover rounded-lg';
            preview.appendChild(img);
        });
    });

    updateStep();
})();
</script>
@endpush
@endsection
