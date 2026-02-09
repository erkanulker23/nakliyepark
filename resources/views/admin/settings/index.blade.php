@extends('layouts.admin')

@section('title', 'Ayarlar')
@section('page_heading', 'Site, SEO ve sistem ayarları')

@section('content')
<div class="max-w-2xl space-y-8">
    <p class="text-slate-600 text-sm">Site logosu, SEO alanları ve müşteri/nakliyeci mailleri için SMTP ayarları.</p>

    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="border-b border-slate-200 pb-5">
                <h3 class="font-semibold text-slate-800 mb-3">Site logosu</h3>
                <p class="text-slate-600 text-sm mb-3">Header ve paylaşımlarda kullanılır. Önerilen: 192×192 px veya oranı korunan görsel.</p>
                @if(!empty($settings['site_logo']))
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Mevcut logo" class="h-16 w-auto object-contain rounded-lg border border-slate-200">
                        <span class="text-sm text-slate-500">Mevcut logo</span>
                    </div>
                @endif
                <div class="admin-form-group">
                    <label class="admin-label">Logo yükle</label>
                    <input type="file" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="admin-input py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200">
                    @error('site_logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-500">JPEG, PNG, GIF, WebP veya SVG. En fazla 2 MB.</p>
                </div>
            </div>
            <div class="border-b border-slate-200 pb-5">
                <h3 class="font-semibold text-slate-800 mb-3">SEO ayarları</h3>
                <p class="text-slate-600 text-sm mb-3">Arama sonuçları ve sosyal paylaşımlar için varsayılan meta bilgileri.</p>
                <div class="space-y-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Site başlığı (meta title)</label>
                        <input type="text" name="site_meta_title" value="{{ old('site_meta_title', $settings['site_meta_title']) }}" class="admin-input" placeholder="NakliyePark - Akıllı nakliye ve yük borsası">
                        @error('site_meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Meta açıklama (meta description)</label>
                        <textarea name="site_meta_description" rows="2" maxlength="500" class="admin-input" placeholder="Arama sonuçlarında görünecek kısa açıklama">{{ old('site_meta_description', $settings['site_meta_description']) }}</textarea>
                        @error('site_meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Meta anahtar kelimeler</label>
                        <input type="text" name="site_meta_keywords" value="{{ old('site_meta_keywords', $settings['site_meta_keywords']) }}" class="admin-input" placeholder="nakliye, ev taşıma, yük borsası, ...">
                        @error('site_meta_keywords')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" class="admin-btn-primary">Site ve SEO kaydet</button>
            </div>
        </form>
    </div>

    <div class="admin-card p-6">
        <h3 class="font-semibold text-slate-800 mb-2">Araç sayfaları (SEO ve nasıl çalışır)</h3>
        <p class="text-slate-600 text-sm mb-5">Hacim hesaplama, mesafe hesaplama ve tahmini maliyet sayfaları için meta başlık, açıklama ve "Nasıl çalışır?" metni. Boş bırakırsanız varsayılan başlık kullanılır; içerik alanında <code class="text-xs bg-slate-100 px-1 rounded">&lt;p&gt;</code>, <code class="text-xs bg-slate-100 px-1 rounded">&lt;strong&gt;</code>, <code class="text-xs bg-slate-100 px-1 rounded">&lt;ul&gt;</code>, <code class="text-xs bg-slate-100 px-1 rounded">&lt;li&gt;</code> kullanabilirsiniz.</p>
        <form method="POST" action="{{ route('admin.settings.tool-pages') }}" class="space-y-8">
            @csrf
            @foreach([
                'volume' => ['label' => 'Hacim Hesaplama', 'route' => route('tools.volume')],
                'distance' => ['label' => 'Mesafe Hesaplama', 'route' => route('tools.distance')],
                'cost' => ['label' => 'Tahmini Maliyet', 'route' => route('tools.cost')],
            ] as $slug => $info)
            <div class="border-b border-slate-200 pb-8 last:border-0 last:pb-0">
                <h4 class="font-medium text-slate-800 mb-3">{{ $info['label'] }}</h4>
                <p class="text-xs text-slate-500 mb-3">URL: {{ $info['route'] }}</p>
                <div class="space-y-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Meta başlık</label>
                        <input type="text" name="tool_{{ $slug }}_meta_title" value="{{ old('tool_'.$slug.'_meta_title', $settings['tool_'.$slug.'_meta_title'] ?? '') }}" class="admin-input" placeholder="Örn: {{ $info['label'] }} - NakliyePark">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Meta açıklama (SEO)</label>
                        <textarea name="tool_{{ $slug }}_meta_description" rows="2" maxlength="500" class="admin-input" placeholder="Arama sonuçlarında görünecek kısa açıklama">{{ old('tool_'.$slug.'_meta_description', $settings['tool_'.$slug.'_meta_description'] ?? '') }}</textarea>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Nasıl çalışır? (HTML desteklenir)</label>
                        <textarea name="tool_{{ $slug }}_content" rows="6" class="admin-input font-mono text-sm" placeholder="Sayfada gösterilecek SEO uyumlu açıklama metni. Örn: <p>Bu araç ile...</p>">{{ old('tool_'.$slug.'_content', $settings['tool_'.$slug.'_content'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="pt-2">
                <button type="submit" class="admin-btn-primary">Araç sayfaları kaydet</button>
            </div>
        </form>
    </div>

    <div class="admin-card p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Mail testi</h3>
        <form method="POST" action="{{ route('admin.settings.test-mail') }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="admin-form-group mb-0 flex-1 min-w-[200px]">
                <label class="admin-label">Test e-postası gönderilecek adres</label>
                <input type="email" name="test_email" value="{{ old('test_email', auth()->user()->email) }}" required class="admin-input" placeholder="test@example.com">
                @error('test_email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="admin-btn-primary">Test maili gönder</button>
        </form>
        <p class="mt-2 text-xs text-slate-500">Önce mail ayarlarını kaydedin, sonra test edin.</p>
    </div>

    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
            @csrf
            <div class="border-b border-slate-200 pb-5">
                <h3 class="font-semibold text-slate-800 mb-3">Gönderici (From)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Gönderen adı</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" class="admin-input" placeholder="NakliyePark">
                        @error('mail_from_name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Gönderen e-posta</label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" class="admin-input" placeholder="noreply@nakliyepark.com">
                        @error('mail_from_address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">SMTP sunucusu</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="admin-form-group">
                            <label class="admin-label">Host</label>
                            <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" class="admin-input" placeholder="smtp.example.com">
                            @error('mail_host')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Port</label>
                            <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" min="1" max="65535" class="admin-input" placeholder="587">
                            @error('mail_port')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Şifreleme</label>
                        <select name="mail_encryption" class="admin-input">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($settings['mail_encryption'] ?? '') === 'null' ? 'selected' : '' }}>Yok</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Kullanıcı adı</label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" class="admin-input" autocomplete="off">
                        @error('mail_username')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Şifre</label>
                        <input type="password" name="mail_password" class="admin-input" placeholder="Değiştirmek için yazın" autocomplete="new-password">
                        @error('mail_password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h3 class="font-semibold text-slate-800 mb-3">Komisyon</h3>
                <div class="admin-form-group max-w-xs">
                    <label class="admin-label">NakliyePark komisyon oranı (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $settings['commission_rate'] ?? '10') }}" min="0" max="100" step="0.01" class="admin-input" placeholder="10">
                    @error('commission_rate')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-500">Firmaların kabul edilen tekliflerinden alınacak oran.</p>
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
