@extends('layouts.nakliyeci')

@section('title', 'Firma bilgilerini düzenle')
@section('page_heading', 'Firma Bilgileri')
@section('page_subtitle', $company->name)

@section('content')
<div class="max-w-3xl">
    @if(!$company->approved_at)
        <div class="admin-alert-error mb-6 rounded-lg border px-4 py-3 text-sm">
            Firma bilgileriniz admin onayı bekliyor. Onaylandıktan sonra ilanlarınız yayında görünecek ve teklif verebileceksiniz.
        </div>
    @endif

    <div class="admin-card p-6">
        <form method="POST" action="{{ route('nakliyeci.company.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            {{-- Firma logosu (admin onayı gerekir) --}}
            <div class="border-b border-slate-200 dark:border-slate-600 pb-6 mb-6">
                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Firma logosu</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Logo yükleyebilirsiniz. Yayına alınması admin onayına bağlıdır.</p>
                @if($company->logo)
                    <div class="flex flex-wrap items-center gap-4 mb-3">
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="Mevcut logo" class="w-24 h-24 rounded-xl object-cover border border-slate-200 dark:border-slate-600">
                        @if(!$company->logo_approved_at)
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Onay bekliyor</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Yayında</span>
                        @endif
                    </div>
                @endif
                <div class="admin-form-group">
                    <label class="admin-label">Yeni logo yükle</label>
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp" class="admin-input">
                    <p class="text-xs text-slate-500 mt-1">JPG, PNG veya WebP. En fazla 2 MB. Admin onayından sonra firma sayfanızda görünür.</p>
                    @error('logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
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
                    <label class="admin-label">Şehir</label>
                    <input type="text" name="city" value="{{ old('city', $company->city) }}" class="admin-input">
                    @error('city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">İlçe</label>
                    <input type="text" name="district" value="{{ old('district', $company->district) }}" class="admin-input">
                    @error('district')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Adres</label>
                <textarea name="address" rows="2" class="admin-input">{{ old('address', $company->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="admin-input" placeholder="5XX XXX XX XX">
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
                    <input type="email" name="email" value="{{ old('email', $company->email) }}" class="admin-input" placeholder="info@firma.com">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="4" class="admin-input">{{ old('description', $company->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-slate-200 dark:border-slate-600 pt-6 mt-6">
                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Verdiği hizmetler</h3>
                <p class="text-sm text-slate-500 mb-3">Firmanızın sunduğu hizmetleri işaretleyin. Müşteri sayfanızda listelenecektir.</p>
                <div class="flex flex-wrap gap-4">
                    @foreach(\App\Models\Company::serviceLabels() as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="services[]" value="{{ $key }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                {{ in_array($key, old('services', $company->services ?? [])) ? 'checked' : '' }}>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('services')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-slate-200 dark:border-slate-600 pt-6 mt-6">
                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">SEO (Arama motoru)</h3>
                <p class="text-sm text-slate-500 mb-3">Firmanızın arama sonuçlarında nasıl görüneceğini belirleyin.</p>
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

            <p class="text-xs text-slate-500">Güncelleme sonrası değişiklikler admin onayından geçecektir.</p>
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('nakliyeci.dashboard') }}" class="admin-btn-secondary">Panele dön</a>
            </div>
        </form>
    </div>

    {{-- Firma galerisi (admin onayı gerekir) --}}
    <div class="mt-6 admin-card p-6">
        <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Firma galerisi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Araç veya iş fotoğraflarınızı ekleyin. Her fotoğraf admin onayından sonra firma sayfanızda yayınlanır.</p>
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <a href="{{ route('nakliyeci.galeri.create') }}" class="admin-btn-primary inline-flex">+ Fotoğraf ekle</a>
            <a href="{{ route('nakliyeci.galeri.index') }}" class="admin-btn-secondary text-sm">Galeriyi yönet</a>
        </div>
        @if($company->vehicleImages->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($company->vehicleImages as $img)
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600">
                        <a href="{{ asset('storage/'.$img->path) }}" target="_blank" class="block aspect-square bg-slate-100 dark:bg-slate-800">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption ?? 'Galeri' }}" class="w-full h-full object-cover">
                        </a>
                        <div class="p-2 flex items-center justify-between gap-2">
                            @if($img->isApproved())
                                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Yayında</span>
                            @else
                                <span class="text-xs font-medium text-amber-600 dark:text-amber-400">Onay bekliyor</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500 py-4">Henüz galeri fotoğrafı yok. &quot;Fotoğraf ekle&quot; ile ekleyebilirsiniz.</p>
        @endif
    </div>

    <div class="mt-6 admin-card p-6">
        <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">İstatistikler</h3>
        <ul class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <li><span class="text-slate-500">Alınan iş sayısı:</span> <strong>{{ $company->acceptedTeklifler()->count() }}</strong></li>
            <li><span class="text-slate-500">Toplam kazanç:</span> <strong>{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</strong></li>
            <li><span class="text-slate-500">NakliyePark komisyonu ({{ $company->commission_rate }}%):</span> <strong>{{ number_format($company->total_commission, 0, ',', '.') }} ₺</strong></li>
        </ul>
    </div>
</div>
@endsection
