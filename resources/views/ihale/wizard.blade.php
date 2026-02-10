@extends('layouts.app')

@section('title', 'İhale Oluştur - Nakliyat Hizmeti Seçin')
@section('meta_description', 'Nakliye ihalesi oluşturun: Evden eve nakliyat veya yük taşıma seçin, rota ve hacim girin. Ücretsiz, üye olmadan teklif alın.')

@section('content')
<div class="page-container py-6 sm:py-10 max-w-xl mx-auto">
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xl shadow-zinc-200/40 dark:shadow-none dark:ring-1 dark:ring-zinc-800">
        {{-- Decorative gradient --}}
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent pointer-events-none" aria-hidden="true"></div>

        {{-- Wizard header --}}
        <div class="relative flex items-center justify-between px-4 sm:px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <button type="button" id="wizard-back" class="flex items-center justify-center w-10 h-10 rounded-xl text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-white transition-colors" aria-label="Geri">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex flex-col items-center gap-0.5">
                <span class="text-[11px] uppercase tracking-wider font-medium text-emerald-600 dark:text-emerald-400" id="step-badge">Adım 1</span>
                <h2 class="font-semibold text-zinc-900 dark:text-white text-lg" id="wizard-title">İhale Başlat</h2>
            </div>
            <a href="{{ route('home') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-white transition-colors" aria-label="Kapat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        {{-- Progress bar --}}
        <div class="relative px-4 sm:px-6 pt-4 pb-1">
            <div class="h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                <div id="progress-fill" class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-400 ease-out" style="width: 12%"></div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2" id="progress-hint">Almak istediğiniz hizmeti seçin</p>
        </div>

        <form id="wizard-form" action="{{ route('ihale.store') }}" method="POST" enctype="multipart/form-data" class="relative px-4 sm:px-6 pb-6">
            @csrf
            <input type="hidden" name="service_type" id="service_type" value="">
            @if(isset($forCompany) && $forCompany)
                <input type="hidden" name="preferred_company_id" value="{{ $forCompany->id }}">
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/80 dark:border-emerald-800/50 text-sm text-emerald-800 dark:text-emerald-200">
                    <strong>{{ $forCompany->name }}</strong> firmasından teklif almak için ihale oluşturuyorsunuz. Onay sonrası bu firma bilgilendirilecektir.
                </div>
            @endif

            {{-- Adım 1: Almak istediğiniz hizmet --}}
            <div data-step-key="service_select" class="step-panel">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Almak istediğiniz hizmet</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50 transition-all duration-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-900/20 has-[:checked]:shadow-sm">
                        <input type="radio" name="service_type_radio" value="evden_eve_nakliyat" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">Evden eve nakliyat</span>
                    </label>
                    <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50 transition-all duration-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-900/20 has-[:checked]:shadow-sm">
                        <input type="radio" name="service_type_radio" value="sehirlerarasi_nakliyat" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">Şehirlerarası nakliyat</span>
                    </label>
                    <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50 transition-all duration-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-900/20 has-[:checked]:shadow-sm">
                        <input type="radio" name="service_type_radio" value="parca_esya_tasimaciligi" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">Parça eşya taşımacılığı</span>
                    </label>
                    <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50 transition-all duration-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-900/20 has-[:checked]:shadow-sm">
                        <input type="radio" name="service_type_radio" value="esya_depolama" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">Eşya depolama</span>
                    </label>
                    <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50 transition-all duration-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-900/20 has-[:checked]:shadow-sm">
                        <input type="radio" name="service_type_radio" value="ofis_tasima" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">Ofis taşıma</span>
                    </label>
                </div>
            </div>

            {{-- Adım: Kaç odalı (sadece evden eve) --}}
            <div data-step-key="room_type" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Kaç odalı ev eşyası taşınacak?</h3>
                <div class="space-y-2">
                    @foreach(['1+1', '2+1', '3+1', '4+1', '5+1', 'Daha büyük', 'Sadece birkaç eşya taşınacak'] as $opt)
                        <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:bg-emerald-900/20">
                            <input type="radio" name="room_type" value="{{ $opt }}" class="w-5 h-5 text-emerald-500 accent-emerald-500">
                            <span class="font-medium text-zinc-800 dark:text-zinc-100">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Adım: Nereden (İl, İlçe, Mahalle API'den) --}}
            <div data-step-key="from" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3" id="from_title">Nereden taşınıyorsun (eski ev nerede)?</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">İl, ilçe ve mahalle seçin.</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">İl *</label>
                        <select name="from_province_id" id="from_province_id" required class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">İlçe</label>
                        <select name="from_district_id" id="from_district_id" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Mahalle</label>
                        <select name="from_neighborhood_id" id="from_neighborhood_id" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Sokak / adres detayı (opsiyonel)</label>
                        <input type="text" name="from_address" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Sokak, bina no vb.">
                    </div>
                </div>
                <input type="hidden" name="from_city" id="from_city" value="">
                <input type="hidden" name="from_district" id="from_district" value="">
                <input type="hidden" name="from_neighborhood" id="from_neighborhood" value="">
            </div>

            {{-- Adım: Nereye (İl, İlçe, Mahalle API'den) --}}
            <div data-step-key="to" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3" id="to_title">Nereye taşınıyorsun (yeni ev nerede)?</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">İl, ilçe ve mahalle seçin.</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">İl *</label>
                        <select name="to_province_id" id="to_province_id" required class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">İlçe</label>
                        <select name="to_district_id" id="to_district_id" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Mahalle</label>
                        <select name="to_neighborhood_id" id="to_neighborhood_id" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Sokak / adres detayı (opsiyonel)</label>
                        <input type="text" name="to_address" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Sokak, bina no vb.">
                    </div>
                </div>
                <input type="hidden" name="to_city" id="to_city" value="">
                <input type="hidden" name="to_district" id="to_district" value="">
                <input type="hidden" name="to_neighborhood" id="to_neighborhood" value="">
            </div>

            {{-- Adım: Evde neler var (sadece evden eve) --}}
            <div data-step-key="ev_esya" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Evinizde neler var?</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Taşınacak eşyaları oda oda kısaca yazın. Nakliyeci buna göre teklif verecek.</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Salonda neler var?</label>
                        <textarea name="ev_salon" rows="2" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: Koltuk takımı, TV ünitesi, kitaplık..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Yatak odasında neler var?</label>
                        <textarea name="ev_yatak_odasi" rows="2" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: Yatak, gardırop, komodin..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Mutfakta neler var?</label>
                        <textarea name="ev_mutfak" rows="2" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: Buzdolabı, fırın, masa..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Diğer (çocuk odası, banyo, balkon vb.)</label>
                        <textarea name="ev_diger" rows="2" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: Çocuk odası dolabı, çamaşır makinesi..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Koli var mı? Yaklaşık kaç adet?</label>
                        <input type="text" name="ev_koli" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: 15-20 koli, yok">
                    </div>
                </div>
                <input type="hidden" name="distance_km" value="">
            </div>

            {{-- Adım: Hacim (m³) - sadece şehirlerarası --}}
            <div data-step-key="volume" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Taşınacak hacim (m³)</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">Oda türlerine göre ekleyin.</p>
                <div class="space-y-2">
                    @foreach($rooms as $room)
                        <div class="flex items-center justify-between card-touch py-3">
                            <span>{{ $room->name }}</span>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-touch w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 vol-minus" data-m3="{{ $room->default_volume_m3 }}">−</button>
                                <span class="vol-display min-w-[2.5rem] text-center" data-default="{{ $room->default_volume_m3 }}">0</span> m³
                                <button type="button" class="btn-touch w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 vol-plus" data-m3="{{ $room->default_volume_m3 }}">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 flex justify-between items-center rounded-xl bg-zinc-100 dark:bg-zinc-800 px-4 py-3">
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">Toplam</span>
                    <span id="total-vol" class="font-semibold text-emerald-600 dark:text-emerald-400">0</span> m³
                </div>
                <input type="hidden" name="volume_m3" id="volume_m3" value="0">
                <input type="hidden" name="distance_km" value="">
            </div>

            {{-- Adım: Eşya / ofis / depo detayı (parça eşya, depolama, ofis) --}}
            <div data-step-key="description_items" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3" id="description_items_title">Taşınacak eşyalar</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3" id="description_items_hint">Taşınacak eşyaları kısaca listeleyin.</p>
                <textarea name="description_items" id="description_items_field" rows="4" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn: Koltuk, kitaplık, 3 koli..."></textarea>
            </div>

            {{-- Adım: Tarih veya fiyat bakıyorum + ek açıklama --}}
            <div data-step-key="date" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Ne zaman?</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Taşınma tarihiniz belli mi yoksa önce fiyat mı karşılaştıracaksınız?</p>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:bg-emerald-900/20">
                            <input type="radio" name="date_preference" value="tarih_araligi" class="w-5 h-5 text-emerald-500 accent-emerald-500" id="date_pref_range">
                            <span class="font-medium text-zinc-800 dark:text-zinc-100">Tarih aralığım belli</span>
                        </label>
                        <label class="flex items-center gap-4 min-h-[52px] px-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:bg-emerald-900/20">
                            <input type="radio" name="date_preference" value="fiyat_bakiyorum" class="w-5 h-5 text-emerald-500 accent-emerald-500" id="date_pref_fiyat">
                            <span class="font-medium text-zinc-800 dark:text-zinc-100">Fiyat bakıyorum, tarih henüz belli değil</span>
                        </label>
                    </div>
                    <div id="date-range-wrap" class="grid grid-cols-1 sm:grid-cols-2 gap-3 hidden">
                        <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Başlangıç tarihi</label>
                            <input type="date" name="move_date" id="move_date" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Bitiş tarihi (opsiyonel)</label>
                            <input type="date" name="move_date_end" id="move_date_end" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Tek gün için boş bırakın">
                        </div>
                    </div>
                    <div id="date-desc-wrap">
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nakliyeci başka neyi bilmeli?</label>
                        <textarea name="description" rows="4" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Eşya tarifi, asansör/merdiven bilgisi..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Adım: Fotoğraf --}}
            <div data-step-key="photos" class="step-panel hidden">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">Eşya fotoğrafı (opsiyonel)</h3>
                <label class="block">
                    <span class="btn-touch w-full border-2 border-dashed border-zinc-200 dark:border-zinc-600 rounded-xl flex flex-col items-center justify-center gap-2 cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors">
                        <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Fotoğraf seç veya çek</span>
                    </span>
                    <input type="file" name="photos[]" accept="image/*" capture="environment" multiple class="hidden" id="photos">
                </label>
                <div id="photo-preview" class="mt-3 flex flex-wrap gap-2"></div>
            </div>

            {{-- Adım: İletişim (misafir için) --}}
            <div data-step-key="contact" class="step-panel hidden" id="step-contact">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-3">İletişim bilgileriniz</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Talebinizi oluşturmak için iletişim bilgilerine ihtiyacımız var. Üye değilseniz bu alanları doldurun.</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Ad Soyad</label>
                        <input type="text" name="guest_contact_name" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Adınız ve soyadınız" value="{{ auth()->user()?->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">E-posta</label>
                        <input type="email" name="guest_contact_email" inputmode="email" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="E-posta adresiniz" value="{{ auth()->user()?->email }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Telefon</label>
                        <input type="tel" name="guest_contact_phone" inputmode="tel" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="5XX XXX XX XX" value="{{ auth()->user()?->phone }}">
                    </div>
                </div>
                {{-- KVKK: Açık rıza ve aydınlatma --}}
                <div class="mt-4 p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="kvkk_consent" value="1" required class="mt-1 w-4 h-4 rounded border-zinc-300 text-emerald-500 focus:ring-emerald-500">
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                            <a href="{{ route('kvkk.aydinlatma') }}" target="_blank" rel="noopener" class="underline font-medium text-emerald-600 dark:text-emerald-400">Kişisel verilerin işlenmesine ilişkin aydınlatma metnini</a> okudum; ad, e-posta, telefon ve adres bilgilerimin talebinin işlenmesi ve firmalarla paylaşılması için açık rızamı veriyorum.
                        </span>
                    </label>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">
                        Kişisel verileriniz, iş tamamlandıktan veya ihale kapatıldıktan sonra en fazla <strong>{{ $dataRetentionMonths ?? 24 }} ay</strong> saklanır; ardından silinir veya anonimleştirilir.
                    </p>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button type="button" id="btn-prev" class="btn-touch flex-1 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 rounded-xl hidden">Geri</button>
                <button type="button" id="btn-next" class="btn-touch flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-medium shadow-sm hover:shadow-md transition-shadow">Devam</button>
                <button type="submit" id="btn-submit" class="btn-touch flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-medium shadow-sm hover:shadow-md transition-shadow hidden">Talebi gönder</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const form = document.getElementById('wizard-form');
    const progressFill = document.getElementById('progress-fill');
    const progressHint = document.getElementById('progress-hint');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');
    const serviceTypeInput = document.getElementById('service_type');

    const SERVICE_STEPS = {
        evden_eve_nakliyat: ['room_type', 'from', 'to', 'ev_esya', 'date', 'photos', 'contact'],
        sehirlerarasi_nakliyat: ['from', 'to', 'volume', 'date', 'photos', 'contact'],
        parca_esya_tasimaciligi: ['from', 'to', 'description_items', 'date', 'photos', 'contact'],
        esya_depolama: ['from', 'description_items', 'date', 'photos', 'contact'],
        ofis_tasima: ['from', 'to', 'description_items', 'date', 'photos', 'contact']
    };
    const FROM_TITLES = { evden_eve_nakliyat: 'Nereden taşınıyorsun (eski ev nerede)?', sehirlerarasi_nakliyat: 'Nereden taşınıyorsun?', parca_esya_tasimaciligi: 'Eşyalar nereden alınacak?', esya_depolama: 'Eşyalar nerede (depolama adresi)?', ofis_tasima: 'Mevcut ofis adresi nerede?' };
    const TO_TITLES = { evden_eve_nakliyat: 'Nereye taşınıyorsun (yeni ev nerede)?', sehirlerarasi_nakliyat: 'Nereye taşınıyorsun?', parca_esya_tasimaciligi: 'Eşyalar nereye gidecek?', ofis_tasima: 'Yeni ofis adresi nerede?' };
    const DESCRIPTION_ITEMS_CONFIG = { parca_esya_tasimaciligi: { title: 'Taşınacak eşyalar', hint: 'Taşınacak eşyaları kısaca listeleyin.', placeholder: 'Örn: Koltuk, kitaplık, 3 koli...' }, esya_depolama: { title: 'Depolanacak eşyalar', hint: 'Depolamak istediğiniz eşyaları kısaca yazın.', placeholder: 'Örn: Mobilya, koli, beyaz eşya...' }, ofis_tasima: { title: 'Ofis büyüklüğü / taşınacaklar', hint: 'Ofis metrekaresi veya taşınacak eşya listesi.', placeholder: 'Örn: 80 m² ofis, 15 masa, arşiv dolabı...' } };

    let step = 1;
    let totalSteps = 1 + 7;
    const apiBase = '{{ url("/api/turkey") }}';
    let fromDistricts = [];
    let toDistricts = [];

    function fillProvinces() {
        fetch(apiBase + '/provinces').then(r => r.json()).then(res => {
            if (!res.data || !res.data.length) return;
            const fromSel = document.getElementById('from_province_id');
            const toSel = document.getElementById('to_province_id');
            if (fromSel && toSel) {
                fromSel.innerHTML = '<option value="">İl seçin</option>';
                toSel.innerHTML = '<option value="">İl seçin</option>';
                res.data.forEach(p => {
                    fromSel.appendChild(new Option(p.name, p.id));
                    toSel.appendChild(new Option(p.name, p.id));
                });
            }
        }).catch(function() {
            const fromSel = document.getElementById('from_province_id');
            const toSel = document.getElementById('to_province_id');
            if (fromSel) fromSel.innerHTML = '<option value="">İller yüklenemedi</option>';
            if (toSel) toSel.innerHTML = '<option value="">İller yüklenemedi</option>';
        });
    }
    function fillDistricts(provinceId, targetSelect, storeKey) {
        const sel = document.getElementById(targetSelect);
        sel.innerHTML = '<option value="">Yükleniyor...</option>';
        if (!provinceId) { sel.innerHTML = '<option value="">Önce il seçin</option>'; return; }
        fetch(apiBase + '/districts?province_id=' + provinceId).then(r => r.json()).then(res => {
            if (!res.data) { sel.innerHTML = '<option value="">İlçe yok</option>'; return; }
            if (storeKey === 'from') fromDistricts = res.data; else toDistricts = res.data;
            sel.innerHTML = '<option value="">İlçe seçin</option>';
            res.data.forEach(d => sel.appendChild(new Option(d.name, d.id)));
        }).catch(() => { sel.innerHTML = '<option value="">Yüklenemedi</option>'; });
    }
    function fillNeighborhoods(districts, districtId, targetSelect) {
        const sel = document.getElementById(targetSelect);
        sel.innerHTML = '<option value="">Mahalle seçin</option>';
        if (!districtId || !districts.length) return;
        const district = districts.find(d => d.id == districtId);
        if (!district || !district.neighborhoods || !district.neighborhoods.length) return;
        district.neighborhoods.forEach(n => sel.appendChild(new Option(n.name, n.id)));
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

    (function() {
        var dateRangeWrap = document.getElementById('date-range-wrap');
        var prefRange = document.getElementById('date_pref_range');
        var prefFiyat = document.getElementById('date_pref_fiyat');
        var moveDate = document.getElementById('move_date');
        var moveDateEnd = document.getElementById('move_date_end');
        if (prefRange && prefFiyat && dateRangeWrap) {
            function toggleDateRange() {
                if (prefRange.checked) {
                    dateRangeWrap.classList.remove('hidden');
                    if (moveDate) moveDate.removeAttribute('disabled');
                    if (moveDateEnd) moveDateEnd.removeAttribute('disabled');
                } else {
                    dateRangeWrap.classList.add('hidden');
                    if (moveDate) { moveDate.value = ''; moveDate.setAttribute('disabled', 'disabled'); }
                    if (moveDateEnd) { moveDateEnd.value = ''; moveDateEnd.setAttribute('disabled', 'disabled'); }
                }
            }
            prefRange.addEventListener('change', toggleDateRange);
            prefFiyat.addEventListener('change', toggleDateRange);
            toggleDateRange();
        }
    })();

    function getService() { const r = form.querySelector('input[name="service_type_radio"]:checked'); return r ? r.value : ''; }
    function getStepsForService() { const s = getService(); return s ? (SERVICE_STEPS[s] || []) : []; }
    function getTotalSteps() { return 1 + getStepsForService().length; }
    function getCurrentStepKey() { if (step === 1) return 'service_select'; const steps = getStepsForService(); return steps[step - 2] || ''; }
    function syncServiceType() { const s = getService(); serviceTypeInput.value = s || ''; }
    form.querySelectorAll('input[name="service_type_radio"]').forEach(radio => { radio.addEventListener('change', () => { syncServiceType(); }); });

    function showStep(s) {
        step = s;
        totalSteps = getTotalSteps();
        const displayTotal = Math.max(totalSteps, 2);
        const stepKey = getCurrentStepKey();
        form.querySelectorAll('.step-panel').forEach(el => { el.classList.toggle('hidden', el.dataset.stepKey !== stepKey); });
        progressFill.style.width = (step / displayTotal * 100) + '%';
        btnPrev.classList.toggle('hidden', step <= 1);
        if (step === 1) {
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        } else {
            btnNext.classList.toggle('hidden', step >= totalSteps);
            btnSubmit.classList.toggle('hidden', step !== totalSteps);
        }
        const stepBadge = document.getElementById('step-badge');
        if (stepBadge) stepBadge.textContent = 'Adım ' + step + (totalSteps > 1 ? ' / ' + totalSteps : '');
        if (step === 1) progressHint.textContent = 'Almak istediğiniz hizmeti seçin';
        else if (step === totalSteps) progressHint.textContent = 'İletişim bilgileriniz';
        else progressHint.textContent = 'Adım ' + step + ' / ' + totalSteps;
        const service = getService();
        if (step >= 2 && service) {
            const fromTitle = document.getElementById('from_title'); if (fromTitle && FROM_TITLES[service]) fromTitle.textContent = FROM_TITLES[service];
            const toTitle = document.getElementById('to_title'); if (toTitle && TO_TITLES[service]) toTitle.textContent = TO_TITLES[service];
        }
        if (stepKey === 'description_items' && service && DESCRIPTION_ITEMS_CONFIG[service]) {
            const cfg = DESCRIPTION_ITEMS_CONFIG[service];
            const titleEl = document.getElementById('description_items_title'); if (titleEl) titleEl.textContent = cfg.title;
            const hintEl = document.getElementById('description_items_hint'); if (hintEl) hintEl.textContent = cfg.hint;
            const field = document.getElementById('description_items_field'); if (field) field.placeholder = cfg.placeholder;
        }
        if (stepKey === 'to') document.getElementById('to_province_id').required = true;
    }

    function validateStep() {
        if (step === 1) { if (!getService()) { alert('Lütfen bir hizmet seçin.'); return false; } syncServiceType(); return true; }
        const stepKey = getCurrentStepKey();
        if (stepKey === 'room_type' && !form.querySelector('input[name="room_type"]:checked')) { alert('Lütfen oda tipi seçin.'); return false; }
        if (stepKey === 'from' && !document.getElementById('from_city').value.trim()) { alert('Lütfen il seçin.'); return false; }
        if (stepKey === 'to' && !document.getElementById('to_city').value.trim()) { alert('Lütfen il seçin.'); return false; }
        if (stepKey === 'volume' && parseFloat(form.querySelector('#volume_m3').value) <= 0) { alert('En az bir oda için hacim ekleyin.'); return false; }
        if (stepKey === 'ev_esya') { /* tüm alanlar opsiyonel */ }
        if (stepKey === 'contact') {
            const name = form.querySelector('input[name="guest_contact_name"]');
            const email = form.querySelector('input[name="guest_contact_email"]');
            const consent = form.querySelector('input[name="kvkk_consent"]');
            if (!name.value.trim() || !email.value.trim()) { alert('Ad soyad ve e-posta zorunludur.'); return false; }
            if (!consent || !consent.checked) { alert('Kişisel verilerin işlenmesi için açık rıza vermeniz gerekmektedir.'); return false; }
        }
        return true;
    }

    btnPrev.addEventListener('click', () => { if (step > 1) showStep(step - 1); });
    document.getElementById('wizard-back').addEventListener('click', () => { if (step > 1) showStep(step - 1); });
    btnNext.addEventListener('click', () => {
        if (!validateStep()) return;
        if (step < getTotalSteps()) showStep(step + 1);
    });

    form.querySelectorAll('.vol-plus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const m3 = parseFloat(btn.dataset.m3);
            const disp = form.querySelectorAll('.vol-display')[i];
            disp.textContent = parseInt(disp.textContent || '0') + 1;
            let total = 0;
            form.querySelectorAll('.vol-display').forEach(d => total += parseInt(d.textContent || '0') * parseFloat(d.dataset.default));
            document.getElementById('total-vol').textContent = total.toFixed(1);
            document.getElementById('volume_m3').value = total.toFixed(2);
        });
    });
    form.querySelectorAll('.vol-minus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const disp = form.querySelectorAll('.vol-display')[i];
            const n = Math.max(0, parseInt(disp.textContent || '0') - 1);
            disp.textContent = n;
            let total = 0;
            form.querySelectorAll('.vol-display').forEach(d => total += parseInt(d.textContent || '0') * parseFloat(d.dataset.default));
            document.getElementById('total-vol').textContent = total.toFixed(1);
            document.getElementById('volume_m3').value = total.toFixed(2);
        });
    });

    document.getElementById('photos').addEventListener('change', function() {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '';
        [].slice.call(this.files, 0, 6).forEach(f => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            img.className = 'w-16 h-16 object-cover rounded-lg';
            preview.appendChild(img);
        });
    });

    form.addEventListener('submit', function() {
        syncServiceType();
        const service = getService();
        if (service === 'esya_depolama') {
            document.getElementById('to_city').value = '';
            var toProv = document.getElementById('to_province_id'); if (toProv) toProv.removeAttribute('required');
        }
        if (service === 'evden_eve_nakliyat') {
            var volInput = document.getElementById('volume_m3');
            if (volInput) volInput.value = '0';
        }
        var prefFiyat = document.querySelector('input[name="date_preference"][value="fiyat_bakiyorum"]');
        if (prefFiyat && prefFiyat.checked) {
            var moveDate = document.getElementById('move_date');
            var moveDateEnd = document.getElementById('move_date_end');
            if (moveDate) moveDate.removeAttribute('disabled');
            if (moveDateEnd) moveDateEnd.removeAttribute('disabled');
            if (moveDate) moveDate.value = '';
            if (moveDateEnd) moveDateEnd.value = '';
        }
    });

    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    if (isLoggedIn) {
        var stepContact = document.getElementById('step-contact');
        if (stepContact) {
            var nameInp = stepContact.querySelector('input[name="guest_contact_name"]');
            var emailInp = stepContact.querySelector('input[name="guest_contact_email"]');
            if (nameInp) nameInp.removeAttribute('required');
            if (emailInp) emailInp.removeAttribute('required');
        }
    }
    showStep(1);
})();
</script>
@endpush
@endsection
