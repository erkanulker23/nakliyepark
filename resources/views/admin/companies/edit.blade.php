@extends('layouts.admin')

@section('title', 'Firma düzenle')
@section('page_heading', 'Firma düzenle')
@section('page_subtitle', $company->name)

@section('content')
<div class="max-w-3xl space-y-4 min-w-0">
    <div class="flex flex-wrap items-center gap-3">
        @if($company->approved_at && !empty($company->slug))
            <a href="{{ route('firmalar.show', $company->slug) }}" target="_blank" rel="noopener" class="admin-btn-secondary text-sm">Firma sayfasına git</a>
        @endif
        <a href="{{ route('admin.companies.index') }}" class="text-slate-600 dark:text-slate-400 hover:underline text-sm">Firmalar listesine dön</a>
    </div>
    @if($company->isBlocked())
        <div class="admin-alert-error rounded-lg px-4 py-3">
            <strong>Üyelik askıda.</strong>
            @if($company->blocked_reason)
                <span class="block mt-1 text-sm">Sebep: {{ $company->blocked_reason }}</span>
            @endif
            Askıyı kaldırmak için aşağıdaki butonu kullanın.
        </div>
        <form method="POST" action="{{ route('admin.blocklist.unblock-company', $company) }}" class="inline">
            @csrf
            <button type="submit" class="admin-btn-primary">Üyelik askısını kaldır</button>
        </form>
    @else
        <div class="admin-card p-4 mb-4 border border-amber-200 dark:border-amber-800 bg-amber-50/50 dark:bg-amber-900/20 rounded-lg">
            <h3 class="font-medium text-slate-800 dark:text-slate-200 mb-2">Nakliyeci üyeliğini askıya al</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Borç, sözleşme ihlali veya diğer sebeplerle üyeliği askıya alabilirsiniz. Askıda firma sitede görünmez ve teklif veremez.</p>
            <form method="POST" action="{{ route('admin.blocklist.block-company', $company) }}" class="space-y-3" onsubmit="return confirm('Bu nakliyecinin üyeliğini askıya almak istediğinize emin misiniz?');">
                @csrf
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="min-w-[180px]">
                        <label class="admin-label text-xs">Sebep türü</label>
                        <select name="blocked_reason_type" class="admin-input py-2 w-full">
                            <option value="">— Seçin (isteğe bağlı) —</option>
                            @foreach(\App\Models\Company::blockedReasonLabels() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="admin-label text-xs">Açıklama (isteğe bağlı)</label>
                        <input type="text" name="blocked_reason" class="admin-input py-2 w-full" placeholder="Örn: Ödenmemiş komisyon, detay..." maxlength="500">
                    </div>
                    <button type="submit" class="admin-btn-danger">Üyeliği askıya al</button>
                </div>
            </form>
        </div>
    @endif
    @if($company->hasPendingChanges())
        @php $p = $company->pending_changes; @endphp
        <div class="admin-card p-5 mb-4 border-2 border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/20 rounded-lg">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                Nakliyecinin gönderdiği bekleyen değişiklikler
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Gönderim: {{ $company->pending_changes_at?->format('d.m.Y H:i') }}. Aşağıdaki değişiklikler onaylanana kadar firma sayfası mevcut haliyle yayında.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-4">
                @if(isset($p['name']))<div><span class="text-slate-500">Firma adı:</span> <strong>{{ $p['name'] }}</strong></div>@endif
                @if(isset($p['city']) || isset($p['district']))<div><span class="text-slate-500">Şehir / İlçe:</span> {{ trim(($p['city'] ?? '') . (isset($p['district']) && $p['district'] ? ', ' . $p['district'] : '')) ?: '—' }}</div>@endif
                @if(isset($p['phone']))<div><span class="text-slate-500">Telefon:</span> {{ $p['phone'] }}</div>@endif
                @if(isset($p['email']))<div><span class="text-slate-500">E-posta:</span> {{ $p['email'] }}</div>@endif
                @if(!empty($p['remove_logo']))<div class="sm:col-span-2"><span class="text-red-600 dark:text-red-400 font-medium">Nakliyeci logoyu kaldırmak istiyor.</span></div>@endif
                @if(!empty($p['logo']))<div class="sm:col-span-2"><span class="text-slate-500">Yeni logo:</span> <img src="{{ asset('storage/'.$p['logo']) }}" alt="Bekleyen logo" class="inline-block w-16 h-16 rounded-lg object-cover border border-slate-200 dark:border-slate-600 mt-1"></div>@endif
            </div>
            <form method="POST" action="{{ route('admin.companies.approve-pending', $company) }}" class="inline" onsubmit="return confirm('Bu değişiklikleri onaylayıp yayına almak istediğinize emin misiniz?');">
                @csrf
                <button type="submit" class="admin-btn-primary">Değişiklikleri onayla ve yayına al</button>
            </form>
        </div>
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
                <button type="button" class="company-edit-tab px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-700 -mb-px"
                    data-tab="map-reviews"
                    role="tab" aria-selected="false">Harita & Yorumlar</button>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Google Harita linki ve yorum bilgileri için <strong>Harita & Yorumlar</strong> sekmesine tıklayın.</p>
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
                        <input type="checkbox" name="approved" value="1" {{ old('approved', $company->approved_at ? '1' : '0') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="admin-label mb-0">Firma onaylı (sitede yayınlansın)</span>
                    </label>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Onaylı olmayan firmalar sitede ve /nakliye-firmalari/... sayfasında görünmez (404). Yeni nakliyeci kayıtları önce admin onayı gerektirir.</p>
                </div>
                @if($company->user)
                <div class="admin-form-group">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="send_credentials_email" value="1" {{ old('send_credentials_email') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="admin-label mb-0">Nakliyeciye giriş bilgileri e-postası gönder</span>
                    </label>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">İşaretlerseniz firmanın e-posta adresine giriş sayfası ve “şifremi oluştur” linki gönderilir. Nakliyeci linke tıklayarak kendi şifresini belirler.</p>
                </div>
                @endif
                <div class="admin-form-group pt-2 border-t border-slate-200 dark:border-slate-600">
                    <p class="admin-label mb-2">Doğrulama rozetleri (firma sayfasında gösterilir)</p>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="email_verified" value="0">
                            <input type="checkbox" name="email_verified" value="1" {{ old('email_verified', $company->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">E-posta adresi doğrulandı</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="phone_verified" value="0">
                            <input type="checkbox" name="phone_verified" value="1" {{ old('phone_verified', $company->phone_verified_at ? '1' : '0') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Telefon numarası doğrulandı</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="official_company_verified" value="0">
                            <input type="checkbox" name="official_company_verified" value="1" {{ old('official_company_verified', $company->official_company_verified_at ? '1' : '0') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Resmi şirket bilgileri (vergi no / vergi dairesi) doğrulandı</span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">İşaretlediğiniz alanlar firma detay sayfasında "Doğrulama bilgileri" olarak gösterilir. Firma onayından bağımsız olarak tek tek işaretleyebilir veya kaldırabilirsiniz.</p>
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

            {{-- Sekme: Harita & Dış yorumlar (Google / Yandex) --}}
            <div id="tab-map-reviews" class="company-edit-pane hidden space-y-5" role="tabpanel">
                <p class="text-sm text-slate-600 dark:text-slate-400">Firma detay sayfasında harita ve Google / Yandex yorum kartları gösterilir. Yönetici bu alanları doldurarak firmanın Google Harita ve yorum sayfası linkini ekleyebilir.</p>
                <div class="admin-form-group">
                    <label class="admin-label">Google Harita URL</label>
                    <input type="text" name="google_maps_url" value="{{ old('google_maps_url', $company->google_maps_url) }}" class="admin-input" placeholder="https://www.google.com/maps/place/...">
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Firmanın Google Harita sayfası linki. Firma detayda &quot;Haritada konum&quot; ve &quot;Google Haritada Aç&quot; olarak kullanılır.</p>
                    @error('google_maps_url')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="border-t border-slate-200 dark:border-slate-600 pt-5">
                    <h4 class="font-medium text-slate-800 dark:text-slate-200 mb-3">Google yorumları (firma sayfasında kart olarak gösterilir)</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Puan ve yorum sayısının <strong>Google'dan orijinal</strong> gelmesi için aşağıdaki butona tıklayın. (.env içinde <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">GOOGLE_PLACES_API_KEY</code> tanımlı olmalı.) Manuel giriş yaparsanız veri &quot;doğrulanmamış&quot; olarak işaretlenir.</p>
                    <form method="POST" action="{{ route('admin.companies.fetch-google-reviews', $company) }}" class="inline-block mb-4">
                        @csrf
                        <button type="submit" class="admin-btn-primary text-sm">Google'dan puan ve yorum sayısını getir</button>
                    </form>
                    @if($company->google_reviews_fetched_at)
                        <p class="text-xs text-emerald-600 dark:text-emerald-400">Son alım: {{ $company->google_reviews_fetched_at->locale('tr')->diffForHumans() }}</p>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="admin-form-group">
                            <label class="admin-label">Google yorumlar sayfası URL</label>
                            <input type="text" name="google_reviews_url" value="{{ old('google_reviews_url', $company->google_reviews_url) }}" class="admin-input" placeholder="https://g.page/... veya Google işletme yorum linki">
                            @error('google_reviews_url')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Google puan (1–5)</label>
                            <input type="number" name="google_rating" value="{{ old('google_rating', $company->google_rating) }}" class="admin-input" min="0" max="5" step="0.1" placeholder="4.2">
                            @error('google_rating')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Google yorum sayısı</label>
                            <input type="number" name="google_review_count" value="{{ old('google_review_count', $company->google_review_count) }}" class="admin-input" min="0" placeholder="156">
                            @error('google_review_count')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-600 pt-5">
                    <h4 class="font-medium text-slate-800 dark:text-slate-200 mb-3">Yandex yorumları (isteğe bağlı)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="admin-form-group">
                            <label class="admin-label">Yandex yorumlar URL</label>
                            <input type="text" name="yandex_reviews_url" value="{{ old('yandex_reviews_url', $company->yandex_reviews_url) }}" class="admin-input" placeholder="https://yandex.com.tr/maps/...">
                            @error('yandex_reviews_url')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Yandex puan (1–5)</label>
                            <input type="number" name="yandex_rating" value="{{ old('yandex_rating', $company->yandex_rating) }}" class="admin-input" min="0" max="5" step="0.1" placeholder="4.0">
                            @error('yandex_rating')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Yandex yorum sayısı</label>
                            <input type="number" name="yandex_review_count" value="{{ old('yandex_review_count', $company->yandex_review_count) }}" class="admin-input" min="0" placeholder="8">
                            @error('yandex_review_count')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        var provincesUrl = {!! json_encode(route('api.turkey.provinces')) !!};
        var districtsUrl = {!! json_encode(route('api.turkey.districts')) !!};
        @php
            $fallbackIlList = array_keys(config('turkey_city_coordinates', []));
            if (empty($fallbackIlList)) {
                $fallbackIlList = ['Adana','Adıyaman','Afyonkarahisar','Ağrı','Aksaray','Amasya','Ankara','Antalya','Ardahan','Artvin','Aydın','Balıkesir','Bartın','Batman','Bayburt','Bilecik','Bingöl','Bitlis','Bolu','Burdur','Bursa','Çanakkale','Çankırı','Çorum','Denizli','Diyarbakır','Düzce','Edirne','Elazığ','Erzincan','Erzurum','Eskişehir','Gaziantep','Giresun','Gümüşhane','Hakkari','Hatay','Iğdır','Isparta','İstanbul','İzmir','Kahramanmaraş','Karabük','Karaman','Kars','Kastamonu','Kayseri','Kırıkkale','Kırklareli','Kırşehir','Kilis','Kocaeli','Konya','Kütahya','Malatya','Manisa','Mardin','Mersin','Muğla','Muş','Nevşehir','Niğde','Ordu','Osmaniye','Rize','Sakarya','Samsun','Siirt','Sinop','Sivas','Şanlıurfa','Şırnak','Tekirdağ','Tokat','Trabzon','Tunceli','Uşak','Van','Yalova','Yozgat','Zonguldak'];
            }
        @endphp
        var fallbackProvinces = @json($fallbackIlList);
        var currentCity = {!! json_encode(old('city', $company->city)) !!};
        var currentDistrict = {!! json_encode(old('district', $company->district)) !!};
        var provSel = document.getElementById('company_province_id');
        var distSel = document.getElementById('company_district_id');
        var cityInp = document.getElementById('company_city');
        var distInp = document.getElementById('company_district');
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
            fetch(districtsUrl + '?province_id=' + encodeURIComponent(provinceId)).then(function(r) { return r.json(); }).then(function(res) {
                var data = res.data;
                if (Object.prototype.toString.call(data) === '[object Object]') data = Object.values(data);
                distSel.innerHTML = '<option value="">İlçe seçin</option>';
                (data || []).forEach(function(d) { distSel.appendChild(new Option(d.name, d.id)); });
                selectByText(distSel, currentDistrict);
                syncHidden();
            }).catch(function() {
                distSel.innerHTML = '<option value="">İlçe listesi alınamadı</option>';
                syncHidden();
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
            fallbackProvinces.forEach(function(name) { provSel.appendChild(new Option(name, name)); });
            selectByText(provSel, currentCity);
            syncHidden();
            distSel.innerHTML = '<option value="">İlçe (API gerekli)</option>';
        }
        fillProvincesFromApi();
        provSel.addEventListener('change', function() {
            distSel.innerHTML = '<option value="">Önce il seçin</option>';
            distInp.value = '';
            if (this.value) {
                var isId = /^\d+$/.test(String(this.value));
                if (isId) loadDistricts(this.value);
                else { cityInp.value = this.value; syncHidden(); }
            } else cityInp.value = '';
            syncHidden();
        });
        distSel.addEventListener('change', syncHidden);
    });
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

    {{-- Logo onayı --}}
    @if($company->logo)
        <div class="mt-6 admin-card p-6">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Firma logosu</h3>
            <div class="flex flex-wrap items-center gap-4">
                <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logo" class="w-24 h-24 rounded-xl object-cover border border-slate-200 dark:border-slate-600">
                @if($company->logo_approved_at)
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Yayında</span>
                @else
                    <form method="POST" action="{{ route('admin.companies.approve-logo', $company) }}" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn-primary text-sm">Logoyu onayla</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    {{-- Firma galerisi: her zaman göster, admin fotoğraf ekleyebilir / onaylayabilir / kaldırabilir --}}
    <div class="mt-6 admin-card p-6">
        <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Firma galerisi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Nakliyecinin yapabildiği gibi buradan da fotoğraf ekleyebilirsiniz. Eklediğiniz fotoğraflar otomatik onaylı olur. Nakliyecinin yüklediği fotoğrafları tek tek veya toplu onaylayabilir, istemediğinizi kaldırabilirsiniz.</p>

        <form method="POST" action="{{ route('admin.companies.store-gallery', $company) }}" enctype="multipart/form-data" class="mb-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-600">
            @csrf
            <div class="flex flex-wrap items-end gap-4">
                <div class="min-w-[200px] flex-1">
                    <label class="admin-label text-xs">Fotoğraf ekle</label>
                    <input type="file" name="images[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple class="admin-input text-sm py-2">
                    @error('images')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    @error('images.*')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="min-w-[180px]">
                    <label class="admin-label text-xs">Ortak açıklama (opsiyonel)</label>
                    <input type="text" name="caption" value="{{ old('caption') }}" class="admin-input text-sm py-2" placeholder="Araç / iş fotoğrafı">
                </div>
                <button type="submit" class="admin-btn-primary text-sm">Yükle</button>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">JPG, PNG veya WebP. Dosya başı en fazla 5 MB. Birden fazla seçebilirsiniz.</p>
        </form>

        @if($company->vehicleImages->count() > 0)
            @php $unapprovedCount = $company->vehicleImages->whereNull('approved_at')->count(); @endphp
            @if($unapprovedCount > 0)
                <form method="POST" action="{{ route('admin.companies.approve-gallery-all', $company) }}" class="inline mb-4">
                    @csrf
                    <button type="submit" class="admin-btn-primary text-sm">Hepsini onayla ({{ $unapprovedCount }} fotoğraf)</button>
                </form>
            @endif
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($company->vehicleImages as $img)
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 group relative">
                        <a href="{{ asset('storage/'.$img->path) }}" target="_blank" class="block aspect-square bg-slate-100 dark:bg-slate-800">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="" class="w-full h-full object-cover">
                        </a>
                        <div class="p-2 flex items-center justify-between gap-2 flex-wrap bg-slate-50 dark:bg-slate-800/50">
                            @if($img->approved_at)
                                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Yayında</span>
                            @else
                                <form method="POST" action="{{ route('admin.companies.approve-gallery-image', [$company, $img->id]) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">Onayla</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.companies.destroy-gallery-image', [$company, $img->id]) }}" class="inline" onsubmit="return confirm('Bu fotoğrafı galeriden kaldırmak istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Kaldır</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500 dark:text-slate-400 py-4">Henüz galeri fotoğrafı yok. Yukarıdaki formdan fotoğraf yükleyebilirsiniz.</p>
        @endif
    </div>

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
