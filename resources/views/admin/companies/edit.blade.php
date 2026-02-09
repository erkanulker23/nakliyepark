@extends('layouts.admin')

@section('title', 'Firma düzenle')
@section('page_heading', 'Firma düzenle')
@section('page_subtitle', $company->name)

@section('content')
<div class="max-w-3xl space-y-4">
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
        <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="space-y-5">
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
            <div class="admin-form-group">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="approved" value="1" {{ $company->approved_at ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="admin-label mb-0">Firma onaylı</span>
                </label>
            </div>
            <div class="border-t border-slate-200 pt-6 mt-6">
                <h3 class="font-semibold text-slate-800 mb-3">SEO</h3>
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
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.companies.index') }}" class="admin-btn-secondary">İptal</a>
                <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" class="inline" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">Firmayı sil</button>
                </form>
            </div>
        </form>
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
