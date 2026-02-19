@extends('layouts.nakliyeci')

@section('title', 'Firma bilgilerini düzenle')
@section('page_heading', 'Firma Bilgileri')
@section('page_subtitle', $company->name)

@section('content')
@php
    $pending = $company->pending_changes ?? [];
@endphp
<div class="max-w-2xl space-y-6 pb-24">
    @if(!$company->approved_at)
        <div class="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50/80 dark:bg-amber-900/20 px-4 py-4 text-sm text-amber-800 dark:text-amber-200">
            Firma bilgileriniz admin onayı bekliyor. Onaylandıktan sonra ilanlarınız yayında görünecek ve teklif verebileceksiniz.
        </div>
    @endif
    @if($company->hasPendingChanges())
        <div class="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50/80 dark:bg-amber-900/20 px-4 py-4 text-sm text-amber-800 dark:text-amber-200">
            <strong>Bekleyen değişiklikleriniz</strong> admin onayına gönderildi ({{ $company->pending_changes_at?->format('d.m.Y H:i') }}). Onaylanana kadar firma sayfanız mevcut haliyle yayında kalacaktır.
        </div>
    @endif

    <form method="POST" action="{{ route('nakliyeci.company.update') }}" enctype="multipart/form-data" class="space-y-6" id="company-edit-form">
        @csrf
        @method('PUT')

        {{-- 1. Logo --}}
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
            <h2 class="text-base font-bold text-[var(--panel-text)] mb-1">Logo</h2>
            <p class="text-sm text-[var(--panel-text-muted)] mb-4">Önerilen: 400×400 px (kare), max 2 MB. JPG, PNG veya WebP.</p>
            @php
                $currentLogo = (!empty($pending['remove_logo'])) ? null : ($pending['logo'] ?? $company->logo);
            @endphp
            @if($currentLogo)
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <img src="{{ asset('storage/'.$currentLogo) }}" alt="Logo" class="w-20 h-20 rounded-2xl object-cover border border-[var(--panel-border)]">
                    @if(!empty($pending['logo']))
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200">Onay bekliyor</span>
                    @elseif(!empty($pending['remove_logo']))
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200">Silme talebi</span>
                    @elseif(!$company->logo_approved_at)
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200">Onay bekliyor</span>
                    @else
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200">Yayında</span>
                    @endif
                </div>
                <label class="flex items-center gap-3 min-h-[44px] cursor-pointer mb-4">
                    <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500 w-5 h-5" {{ old('remove_logo') || !empty($pending['remove_logo']) ? 'checked' : '' }}>
                    <span class="text-sm text-[var(--panel-text)]">Mevcut logoyu sil (onay sonrası kaldırılır)</span>
                </label>
            @endif
            <label class="block">
                <span class="sr-only">Yeni logo</span>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp" class="block w-full text-sm text-[var(--panel-text-muted)] file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[var(--panel-primary)] file:text-white file:cursor-pointer">
            </label>
            @error('logo')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- 2. Temel bilgiler --}}
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
            <h2 class="text-base font-bold text-[var(--panel-text)] mb-4">Temel bilgiler</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Firma adı *</label>
                    <input type="text" name="name" value="{{ old('name', $pending['name'] ?? $company->name) }}" required class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)] focus:border-transparent">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Vergi no</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number', $pending['tax_number'] ?? $company->tax_number) }}" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('tax_number')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Vergi / Veri dairesi</label>
                        <input type="text" name="tax_office" value="{{ old('tax_office', $pending['tax_office'] ?? $company->tax_office) }}" placeholder="Örn: Kadıköy VD" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('tax_office')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">İl</label>
                        <select id="nakliyeci_company_province" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                            <option value="">İl seçin</option>
                        </select>
                        <p class="mt-1 text-xs text-[var(--panel-text-muted)]">Türkiye il listesi API'den yüklenir.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">İlçe</label>
                        <select id="nakliyeci_company_district" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]" disabled>
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="city" id="nakliyeci_company_city" value="{{ old('city', $pending['city'] ?? $company->city) }}">
                <input type="hidden" name="district" id="nakliyeci_company_district_value" value="{{ old('district', $pending['district'] ?? $company->district) }}">
                @error('city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('district')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Adres</label>
                    <textarea name="address" rows="2" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">{{ old('address', $pending['address'] ?? $company->address) }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- 3. İletişim --}}
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
            <h2 class="text-base font-bold text-[var(--panel-text)] mb-4">İletişim</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone', $pending['phone'] ?? $company->phone) }}" placeholder="5XX XXX XX XX" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">İkinci telefon</label>
                        <input type="text" name="phone_2" value="{{ old('phone_2', $pending['phone_2'] ?? $company->phone_2) }}" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('phone_2')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $pending['whatsapp'] ?? $company->whatsapp) }}" placeholder="5XX XXX XX XX" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('whatsapp')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">E-posta</label>
                        <input type="email" name="email" value="{{ old('email', $pending['email'] ?? $company->email) }}" placeholder="info@firma.com" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                        @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Açıklama</label>
                    <textarea name="description" rows="4" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]" placeholder="Firmanızı kısaca tanıtın">{{ old('description', $pending['description'] ?? $company->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- 4. Hizmetler --}}
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
            <h2 class="text-base font-bold text-[var(--panel-text)] mb-1">Verdiği hizmetler</h2>
            <p class="text-sm text-[var(--panel-text-muted)] mb-4">Müşteri sayfanızda listelenecek.</p>
            <div class="flex flex-wrap gap-3">
                @foreach(\App\Models\Company::serviceLabels() as $key => $label)
                    <label class="flex items-center gap-2.5 min-h-[44px] px-4 py-2.5 rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] cursor-pointer hover:border-[var(--panel-primary)] transition-colors">
                        <input type="checkbox" name="services[]" value="{{ $key }}" class="rounded border-slate-300 text-[var(--panel-primary)] focus:ring-[var(--panel-primary)] w-5 h-5"
                            {{ in_array($key, old('services', $pending['services'] ?? $company->services ?? [])) ? 'checked' : '' }}>
                        <span class="text-sm text-[var(--panel-text)]">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('services')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- 5. SEO (açılır) --}}
        <details class="panel-card rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] overflow-hidden">
            <summary class="p-5 sm:p-6 cursor-pointer list-none flex items-center justify-between gap-2 text-base font-bold text-[var(--panel-text)]">
                <span>SEO (Arama motoru)</span>
                <svg class="details-chevron w-5 h-5 text-[var(--panel-text-muted)] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-0 space-y-4 border-t border-[var(--panel-border)]">
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Meta başlık</label>
                    <input type="text" name="seo_meta_title" value="{{ old('seo_meta_title', $pending['seo_meta_title'] ?? $company->seo_meta_title) }}" placeholder="Arama sonuçlarında görünecek başlık" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                    @error('seo_meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Meta açıklama</label>
                    <textarea name="seo_meta_description" rows="2" maxlength="500" placeholder="Kısa açıklama" class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">{{ old('seo_meta_description', $pending['seo_meta_description'] ?? $company->seo_meta_description) }}</textarea>
                    @error('seo_meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--panel-text)] mb-1.5">Meta anahtar kelimeler</label>
                    <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $pending['seo_meta_keywords'] ?? $company->seo_meta_keywords) }}" placeholder="nakliye, ev taşıma, ..." class="input-touch w-full rounded-xl border border-[var(--panel-border)] bg-[var(--panel-bg)] px-4 py-3 text-[var(--panel-text)] focus:ring-2 focus:ring-[var(--panel-primary)]">
                    @error('seo_meta_keywords')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </details>

        <p class="text-xs text-[var(--panel-text-muted)]">Kaydettiğinizde değişiklikler admin onayına gönderilir. Onay sonrası firma sayfanız güncellenir.</p>

        {{-- Sticky / inline actions --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button type="submit" class="order-1 w-full sm:w-auto min-h-[48px] px-6 py-3 rounded-2xl text-base font-bold bg-[var(--panel-primary)] text-white shadow-lg shadow-emerald-500/25 hover:opacity-95 active:scale-[0.99] transition-all">
                Kaydet
            </button>
            <a href="{{ route('nakliyeci.dashboard') }}" class="order-2 w-full sm:w-auto min-h-[48px] inline-flex items-center justify-center px-6 py-3 rounded-2xl text-base font-medium border border-[var(--panel-border)] bg-[var(--panel-surface)] text-[var(--panel-text)] hover:bg-[var(--panel-bg)] transition-colors">
                Panele dön
            </a>
        </div>
    </form>

    {{-- Galeri --}}
    <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
        <h2 class="text-base font-bold text-[var(--panel-text)] mb-1">Galeri</h2>
        <p class="text-sm text-[var(--panel-text-muted)] mb-4">Araç veya iş fotoğraflarınız. Her fotoğraf admin onayından sonra yayınlanır.</p>
        <div class="flex flex-wrap gap-3 mb-4">
            <a href="{{ route('nakliyeci.galeri.create') }}" class="inline-flex items-center justify-center min-h-[48px] px-5 py-2.5 rounded-2xl text-base font-semibold bg-[var(--panel-primary)] text-white">+ Fotoğraf ekle</a>
            <a href="{{ route('nakliyeci.galeri.index') }}" class="inline-flex items-center justify-center min-h-[48px] px-5 py-2.5 rounded-2xl text-base font-medium border border-[var(--panel-border)] text-[var(--panel-text)]">Galeriyi yönet</a>
        </div>
        @if($company->vehicleImages->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($company->vehicleImages as $img)
                    <div class="rounded-xl overflow-hidden border border-[var(--panel-border)]">
                        <a href="{{ asset('storage/'.$img->path) }}" target="_blank" class="block aspect-square bg-[var(--panel-bg)]">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption ?? 'Galeri' }}" class="w-full h-full object-cover">
                        </a>
                        <div class="p-2 text-xs font-medium {{ $img->isApproved() ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $img->isApproved() ? 'Yayında' : 'Onay bekliyor' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-[var(--panel-text-muted)] py-4">Henüz galeri fotoğrafı yok.</p>
        @endif
    </div>

    {{-- İstatistikler --}}
    <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)]">
        <h2 class="text-base font-bold text-[var(--panel-text)] mb-4">İstatistikler</h2>
        <ul class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <li><span class="text-[var(--panel-text-muted)]">Alınan iş:</span> <strong class="text-[var(--panel-text)]">{{ $company->acceptedTeklifler()->count() }}</strong></li>
            <li><span class="text-[var(--panel-text-muted)]">Toplam kazanç:</span> <strong class="text-[var(--panel-text)]">{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</strong></li>
            <li><span class="text-[var(--panel-text-muted)]">Komisyon ({{ $company->commission_rate }}%):</span> <strong class="text-[var(--panel-text)]">{{ number_format($company->total_commission, 0, ',', '.') }} ₺</strong></li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var provincesUrl = {!! json_encode(route('api.turkey.provinces')) !!};
    var districtsUrl = {!! json_encode(route('api.turkey.districts')) !!};
    var fallbackProvinces = @json(array_keys(config('turkey_city_coordinates', [])) ?: ['Adana','Ankara','Antalya','Aydın','Balıkesir','Bursa','Denizli','Diyarbakır','Gaziantep','Hatay','İstanbul','İzmir','Kayseri','Kocaeli','Konya','Malatya','Manisa','Mardin','Mersin','Muğla','Samsun','Şanlıurfa','Tekirdağ','Trabzon']);
    var currentCity = {!! json_encode(old('city', $pending['city'] ?? $company->city)) !!};
    var currentDistrict = {!! json_encode(old('district', $pending['district'] ?? $company->district)) !!};
    var provSel = document.getElementById('nakliyeci_company_province');
    var distSel = document.getElementById('nakliyeci_company_district');
    var cityInp = document.getElementById('nakliyeci_company_city');
    var distInp = document.getElementById('nakliyeci_company_district_value');
    if (!provSel || !distSel || !cityInp || !distInp) return;

    function selectByText(sel, text) {
        if (!text || !sel) return;
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text === text) { sel.selectedIndex = i; return; }
        }
    }
    function syncHidden() {
        if (provSel.selectedIndex > 0) cityInp.value = provSel.options[provSel.selectedIndex].text;
        if (distSel.selectedIndex > 0) distInp.value = distSel.options[distSel.selectedIndex].text;
    }
    function clearProvincesKeepFirst() {
        while (provSel.options.length > 1) provSel.remove(1);
    }
    function loadDistricts(provinceId) {
        if (!provinceId) return;
        distSel.innerHTML = '<option value="">Yükleniyor...</option>';
        distSel.disabled = true;
        fetch(districtsUrl + '?province_id=' + encodeURIComponent(provinceId)).then(function(r) { return r.json(); }).then(function(res) {
            var data = res.data;
            if (Object.prototype.toString.call(data) === '[object Object]') data = Object.values(data);
            distSel.innerHTML = '<option value="">İlçe seçin</option>';
            (data || []).forEach(function(d) { distSel.appendChild(new Option(d.name, d.id)); });
            selectByText(distSel, currentDistrict);
            syncHidden();
            distSel.disabled = false;
        }).catch(function() {
            distSel.innerHTML = '<option value="">İlçe listesi alınamadı</option>';
            syncHidden();
            distSel.disabled = false;
        });
    }
    function fillProvincesFromApi() {
        clearProvincesKeepFirst();
        fetch(provincesUrl).then(function(r) { return r.json(); }).then(function(res) {
            var data = res.data;
            if (Object.prototype.toString.call(data) === '[object Object]') data = Object.values(data);
            if (data && data.length) {
                data.forEach(function(p) { provSel.appendChild(new Option(p.name, p.id)); });
                selectByText(provSel, currentCity);
                if (provSel.value) loadDistricts(provSel.value);
                else syncHidden();
                return;
            }
            fillProvincesFallback();
        }).catch(function() { fillProvincesFallback(); });
    }
    function fillProvincesFallback() {
        clearProvincesKeepFirst();
        (fallbackProvinces && fallbackProvinces.length ? fallbackProvinces : ['Adana','Ankara','Antalya','İstanbul','İzmir']).forEach(function(name) {
            provSel.appendChild(new Option(name, name));
        });
        selectByText(provSel, currentCity);
        syncHidden();
        distSel.innerHTML = '<option value="">İlçe (API gerekli)</option>';
        distSel.disabled = false;
    }
    fillProvincesFromApi();
    provSel.addEventListener('change', function() {
        distSel.innerHTML = '<option value="">Önce il seçin</option>';
        distInp.value = '';
        if (this.value) {
            var isId = /^\d+$/.test(String(this.value));
            if (isId) loadDistricts(this.value);
            else { cityInp.value = this.value; syncHidden(); }
        } else { cityInp.value = ''; distSel.disabled = true; }
        syncHidden();
    });
    distSel.addEventListener('change', syncHidden);
});
</script>
@endpush
@endsection
