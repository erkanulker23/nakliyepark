@extends('layouts.admin')

@section('title', 'Firma düzenle')
@section('page_heading', 'Firma düzenle')
@section('page_subtitle', $company->name)

@section('content')
<div class="max-w-3xl space-y-4">
    <div class="flex flex-wrap items-center gap-3">
        @if($company->approved_at && $company->slug)
            <a href="{{ route('firmalar.show', $company) }}" target="_blank" rel="noopener" class="admin-btn-secondary text-sm">Firma sayfasına git</a>
        @endif
        <a href="{{ route('admin.companies.index') }}" class="text-slate-600 dark:text-slate-400 hover:underline text-sm">Firmalar listesine dön</a>
    </div>
    @if($company->isBlocked())
        <div class="admin-alert-error rounded-lg px-4 py-3">Bu firma engelli. Engeli kaldırmak için aşağıdaki butonu kullanın.</div>
        <form method="POST" action="{{ route('admin.blocklist.unblock-company', $company) }}" class="inline">
            @csrf
            <button type="submit" class="admin-btn-primary">Engeli kaldır</button>
        </form>
    @else
        <form method="POST" action="{{ route('admin.blocklist.block-company', $company) }}" class="inline" onsubmit="return confirm('Bu firmayı engellemek istediğinize emin misiniz?');">
            @csrf
            <button type="submit" class="admin-btn-danger">Firmayı engelle</button>
        </form>
    @endif
    <div class="admin-card p-6">
        {{-- Sekmeler --}}
        <div class="border-b border-slate-200 dark:border-slate-600 mb-6" role="tablist">
            <div class="flex gap-1">
                <button type="button" class="company-edit-tab px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-emerald-600 text-emerald-600 -mb-px"
                    data-tab="firma"
                    role="tab" aria-selected="true">Firma bilgileri</button>
                <button type="button" class="company-edit-tab px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-700 -mb-px"
                    data-tab="seo"
                    role="tab" aria-selected="false">SEO</button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Sekme: Firma bilgileri --}}
            <div id="tab-firma" class="company-edit-pane space-y-5" role="tabpanel">
                <div class="admin-form-group">
                    <label class="admin-label">Firma adı *</label>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" required class="admin-input">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">Vergi no</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number', $company->tax_number) }}" class="admin-input">
                        @error('tax_number')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Vergi / Veri dairesi</label>
                        <input type="text" name="tax_office" value="{{ old('tax_office', $company->tax_office) }}" class="admin-input" placeholder="Örn: Kadıköy VD">
                        @error('tax_office')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">İl</label>
                        <select id="company_province_id" class="admin-input">
                            <option value="">İl seçin</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Türkiye il listesi API'den yüklenir.</p>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İlçe</label>
                        <select id="company_district_id" class="admin-input">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="city" id="company_city" value="{{ old('city', $company->city) }}">
                <input type="hidden" name="district" id="company_district" value="{{ old('district', $company->district) }}">
                <div class="admin-form-group">
                    <label class="admin-label">Adres</label>
                    <textarea name="address" rows="2" class="admin-input">{{ old('address', $company->address) }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="admin-input">
                        @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">İkinci telefon</label>
                        <input type="text" name="phone_2" value="{{ old('phone_2', $company->phone_2) }}" class="admin-input">
                        @error('phone_2')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $company->whatsapp) }}" class="admin-input" placeholder="5XX XXX XX XX">
                        @error('whatsapp')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">E-posta</label>
                        <input type="email" name="email" value="{{ old('email', $company->email) }}" class="admin-input">
                        @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Açıklama</label>
                    <textarea name="description" rows="4" class="admin-input">{{ old('description', $company->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                @php
                    $serviceLabels = \App\Models\Company::serviceLabels();
                    $companyServices = is_array($company->services ?? null) ? $company->services : [];
                    $selectedServices = old('services', $companyServices);
                @endphp
                <div class="admin-form-group">
                    <label class="admin-label">Verdiği hizmetler</label>
                    <div class="flex flex-wrap gap-3 pt-1">
                        @foreach($serviceLabels as $key => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="services[]" value="{{ $key }}" {{ in_array($key, $selectedServices) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Firmanın sunduğu hizmet türleri; firma sayfasında gösterilir.</p>
                    @error('services')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Abonelik paketi</label>
                    <select name="package" class="admin-input">
                        <option value="">— Paket yok —</option>
                        @foreach($paketler ?? [] as $p)
                            <option value="{{ $p['id'] ?? '' }}" {{ old('package', $company->package) === ($p['id'] ?? '') ? 'selected' : '' }}>{{ $p['name'] ?? $p['id'] }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Firma listesinde ve detayda paket rozeti olarak gösterilir.</p>
                    @error('package')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="approved" value="0">
                        <input type="checkbox" name="approved" value="1" {{ old('approved', $company->approved_at ? '1' : '0') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="admin-label mb-0">Firma onaylı (sitede yayınlansın)</span>
                    </label>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Onaylı olmayan firmalar sitede ve /nakliye-firmalari/... sayfasında görünmez (404). Yeni nakliyeci kayıtları önce admin onayı gerektirir.</p>
                </div>
            </div>

            {{-- Sekme: SEO --}}
            <div id="tab-seo" class="company-edit-pane hidden space-y-5" role="tabpanel">
                <div class="admin-form-group">
                    <label class="admin-label">Meta başlık</label>
                    <input type="text" name="seo_meta_title" value="{{ old('seo_meta_title', $company->seo_meta_title) }}" class="admin-input" placeholder="Arama sonuçlarında görünecek başlık">
                    @error('seo_meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta açıklama</label>
                    <textarea name="seo_meta_description" rows="2" maxlength="500" class="admin-input" placeholder="Arama sonuçlarında görünecek kısa açıklama">{{ old('seo_meta_description', $company->seo_meta_description) }}</textarea>
                    @error('seo_meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta anahtar kelimeler</label>
                    <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $company->seo_meta_keywords) }}" class="admin-input" placeholder="nakliye, ev taşıma, ...">
                    @error('seo_meta_keywords')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2 border-t border-slate-200 dark:border-slate-600">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.companies.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
        <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" class="inline mt-3" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz? Silme nedeni audit log\'a kaydedilir.');">
            <input type="text" name="action_reason" class="admin-input py-1.5 w-48 text-sm mr-2" placeholder="Silme nedeni (isteğe bağlı)" maxlength="1000">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger">Firmayı sil</button>
        </form>
    </div>

    <script>
    (function() {
        var apiBase = '{{ url("/api/turkey") }}';
        var currentCity = {{ json_encode(old('city', $company->city)) }};
        var currentDistrict = {{ json_encode(old('district', $company->district)) }};
        var districtStore = [];
        var provSel = document.getElementById('company_province_id');
        var distSel = document.getElementById('company_district_id');
        var cityInp = document.getElementById('company_city');
        var distInp = document.getElementById('company_district');
        function selectByText(sel, text) {
            if (!text) return;
            for (var i = 0; i < sel.options.length; i++) {
                if (sel.options[i].text === text) { sel.selectedIndex = i; return; }
            }
        }
        function syncHidden() {
            if (provSel.selectedIndex > 0) cityInp.value = provSel.options[provSel.selectedIndex].text;
            if (distSel.selectedIndex > 0) distInp.value = distSel.options[distSel.selectedIndex].text;
        }
        fetch(apiBase + '/provinces').then(function(r) { return r.json(); }).then(function(res) {
            if (!res.data || !res.data.length) return;
            res.data.forEach(function(p) {
                provSel.appendChild(new Option(p.name, p.id));
            });
            selectByText(provSel, currentCity);
            if (provSel.value) loadDistricts(provSel.value);
        });
        function loadDistricts(provinceId) {
            if (!provinceId) return;
            fetch(apiBase + '/districts?province_id=' + provinceId).then(function(r) { return r.json(); }).then(function(res) {
                if (!res.data) return;
                districtStore = res.data;
                distSel.innerHTML = '<option value="">İlçe seçin</option>';
                res.data.forEach(function(d) { distSel.appendChild(new Option(d.name, d.id)); });
                selectByText(distSel, currentDistrict);
                syncHidden();
            });
        }
        provSel.addEventListener('change', function() {
            distSel.innerHTML = '<option value="">Önce il seçin</option>';
            distInp.value = '';
            if (this.value) loadDistricts(this.value);
            else cityInp.value = '';
            syncHidden();
        });
        distSel.addEventListener('change', syncHidden);
    })();
    (function() {
        var tabs = document.querySelectorAll('.company-edit-tab');
        var panes = document.querySelectorAll('.company-edit-pane');
        function showTab(tabId) {
            tabs.forEach(function(b) {
                b.classList.remove('border-emerald-600', 'text-emerald-600');
                b.classList.add('border-transparent', 'text-slate-500');
                b.setAttribute('aria-selected', 'false');
                if (b.getAttribute('data-tab') === tabId) {
                    b.classList.add('border-emerald-600', 'text-emerald-600');
                    b.classList.remove('border-transparent', 'text-slate-500');
                    b.setAttribute('aria-selected', 'true');
                }
            });
            panes.forEach(function(pane) {
                pane.classList.toggle('hidden', pane.id !== 'tab-' + tabId);
            });
        }
        tabs.forEach(function(btn) {
            btn.addEventListener('click', function() { showTab(this.getAttribute('data-tab')); });
        });
        if (document.querySelector('#tab-seo .text-red-500')) showTab('seo');
    })();
    </script>
    <div class="mt-6 admin-card p-6">
        <h3 class="font-semibold text-slate-800 mb-3">İstatistikler</h3>
        <ul class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <li><span class="text-slate-500">Alınan iş sayısı:</span> <strong>{{ $company->acceptedTeklifler()->count() }}</strong></li>
            <li><span class="text-slate-500">Toplam kazanç:</span> <strong>{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</strong></li>
            <li><span class="text-slate-500">NakliyePark komisyonu ({{ $company->commission_rate }}%):</span> <strong>{{ number_format($company->total_commission, 0, ',', '.') }} ₺</strong></li>
        </ul>
    </div>
    <p class="mt-4 text-sm text-slate-500">Kullanıcı: {{ $company->user->name ?? '-' }} ({{ $company->user->email ?? '-' }})</p>
</div>
@endsection
