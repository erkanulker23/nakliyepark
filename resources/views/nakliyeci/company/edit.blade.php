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
        <form method="POST" action="{{ route('nakliyeci.company.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
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
