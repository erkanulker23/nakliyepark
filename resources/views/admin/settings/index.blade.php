@extends('layouts.admin')

@section('title', 'Ayarlar')
@section('page_heading', 'Site, SEO ve sistem ayarları')

@push('styles')
<style>
.settings-tab-panel { display: none; }
.settings-tab-panel.active { display: block; }
</style>
@endpush

@section('content')
<div class="w-full max-w-[1600px]" id="settings-tabs">
    <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Site logosu, SEO, araç sayfaları, mail gönderim ayarları, komisyon ve sistem e-posta şablonları sekmelerde ayrılmıştır.</p>

    {{-- Tab başlıkları --}}
    <nav class="flex flex-wrap gap-1 border-b border-slate-200 dark:border-slate-700 mb-6" role="tablist">
        <button type="button" role="tab" id="tab-site" aria-selected="true" aria-controls="panel-site" data-tab="site"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors rounded-t-lg
            text-emerald-600 dark:text-emerald-400 border-emerald-500 bg-white dark:bg-slate-800">
            Site & SEO
        </button>
        <button type="button" role="tab" id="tab-tools" aria-selected="false" aria-controls="panel-tools" data-tab="tools"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Araç sayfaları
        </button>
        <button type="button" role="tab" id="tab-mail" aria-selected="false" aria-controls="panel-mail" data-tab="mail"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Mail gönderimi
        </button>
        <button type="button" role="tab" id="tab-commission" aria-selected="false" aria-controls="panel-commission" data-tab="commission"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Komisyon
        </button>
        <button type="button" role="tab" id="tab-style" aria-selected="false" aria-controls="panel-style" data-tab="style"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Stil & Scriptler
        </button>
        <button type="button" role="tab" id="tab-mail-templates" aria-selected="false" aria-controls="panel-mail-templates" data-tab="mail-templates"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Mail şablonları
        </button>
        <button type="button" role="tab" id="tab-packages" aria-selected="false" aria-controls="panel-packages" data-tab="packages"
            class="settings-tab px-4 py-3 text-sm font-medium border-b-2 -mb-px border-transparent transition-colors rounded-t-lg
            text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600">
            Paketler
        </button>
    </nav>

    {{-- Tab 1: Site & SEO --}}
    <div role="tabpanel" id="panel-site" class="settings-tab-panel active" aria-labelledby="tab-site">
        <div class="admin-card p-6 max-w-3xl">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="settings_section" value="site">
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Site logosu</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Header ve paylaşımlarda kullanılır. Önerilen: 192×192 px veya oranı korunan görsel.</p>
                    @if(!empty($settings['site_logo']))
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Mevcut logo" class="h-16 w-auto object-contain rounded-lg border border-slate-200 dark:border-slate-600">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Mevcut logo</span>
                        </div>
                    @endif
                    <div class="admin-form-group">
                        <label class="admin-label">Logo yükle (açık tema)</label>
                        <input type="file" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="admin-input py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 dark:file:text-slate-200 hover:file:bg-slate-200 dark:hover:file:bg-slate-500">
                        @error('site_logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">JPEG, PNG, GIF, WebP veya SVG. En fazla 2 MB.</p>
                    </div>
                    <div class="admin-form-group mt-4">
                        <label class="admin-label">Koyu mod logosu (isteğe bağlı)</label>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-2">Kullanıcı koyu tema kullandığında header’da bu logo gösterilir. Boş bırakırsanız normal logo kullanılır.</p>
                        @if(!empty($settings['site_logo_dark']))
                            <div class="mb-2 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $settings['site_logo_dark']) }}" alt="Koyu mod logo" class="h-14 w-auto object-contain rounded-lg border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 p-1">
                                <span class="text-sm text-slate-500 dark:text-slate-400">Mevcut koyu mod logosu</span>
                            </div>
                        @endif
                        <input type="file" name="site_logo_dark" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="admin-input py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-600 file:px-4 file:py-2 file:text-sm">
                        @error('site_logo_dark')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group mt-4">
                        <label class="admin-label">Site favicon</label>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-2">Sekme ve yer imlerinde görünen ikon. Önerilen: 32×32 veya 16×16 PNG/ICO.</p>
                        @if(!empty($settings['site_favicon']))
                            <div class="mb-2 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain rounded border border-slate-200 dark:border-slate-600">
                                <span class="text-sm text-slate-500 dark:text-slate-400">Mevcut favicon</span>
                            </div>
                        @endif
                        <input type="file" name="site_favicon" accept="image/png,image/x-icon,image/gif,image/svg+xml,.ico" class="admin-input py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-600 file:px-4 file:py-2 file:text-sm">
                        @error('site_favicon')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">PNG, ICO, GIF veya SVG. En fazla 1 MB.</p>
                    </div>
                </div>
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">SEO ayarları</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Arama sonuçları ve sosyal paylaşımlar için varsayılan meta bilgileri.</p>
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
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Arama motoru doğrulama</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Google Search Console, Yandex Webmaster ve Bing Webmaster araçlarından aldığınız doğrulama kodlarını buraya yapıştırın. Sadece <strong>content</strong> değerini girin.</p>
                    <div class="grid sm:grid-cols-1 gap-4">
                        <div class="admin-form-group">
                            <label class="admin-label">Google site doğrulama (google-site-verification)</label>
                            <input type="text" name="seo_google_verification" value="{{ old('seo_google_verification', $settings['seo_google_verification'] ?? '') }}" class="admin-input font-mono text-sm" placeholder="Örn: abc123...">
                            @error('seo_google_verification')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Yandex doğrulama (yandex-verification)</label>
                            <input type="text" name="seo_yandex_verification" value="{{ old('seo_yandex_verification', $settings['seo_yandex_verification'] ?? '') }}" class="admin-input font-mono text-sm" placeholder="Örn: abc123...">
                            @error('seo_yandex_verification')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Bing doğrulama (msvalidate.01)</label>
                            <input type="text" name="seo_bing_verification" value="{{ old('seo_bing_verification', $settings['seo_bing_verification'] ?? '') }}" class="admin-input font-mono text-sm" placeholder="Örn: abc123...">
                            @error('seo_bing_verification')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">SEO / &lt;head&gt; kodları</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Sayfa &lt;head&gt; içine eklenecek ek meta etiketleri veya scriptler (doğrulama, schema, analytics snippet vb.). Stil & Scriptler sekmesindeki alanlardan bağımsız olarak sadece SEO/doğrulama için kullanılır.</p>
                    <div class="admin-form-group">
                        <label class="admin-label">Head içi HTML / script</label>
                        <textarea name="seo_head_codes" rows="6" class="admin-input font-mono text-sm w-full" placeholder="Örn: &lt;meta name=&quot;google-site-verification&quot; content=&quot;...&quot;&gt;&#10;&lt;script type=&quot;application/ld+json&quot;&gt;...&lt;/script&gt;">{{ old('seo_head_codes', $settings['seo_head_codes'] ?? '') }}</textarea>
                        @error('seo_head_codes')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="admin-btn-primary">Site ve SEO kaydet</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tab 2: Araç sayfaları --}}
    <div role="tabpanel" id="panel-tools" class="settings-tab-panel" aria-labelledby="tab-tools" hidden>
        <div class="w-full">
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Araç sayfaları için meta başlık, açıklama ve "Nasıl çalışır?" metni. İçerikte <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;p&gt;</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;strong&gt;</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;ul&gt;</code> kullanabilirsiniz.</p>
            <form method="POST" action="{{ route('admin.settings.tool-pages') }}">
                @csrf
                <div class="grid lg:grid-cols-2 gap-6">
                @foreach([
                    'volume' => ['label' => 'Hacim Hesaplama', 'route' => route('tools.volume')],
                    'distance' => ['label' => 'Mesafe Hesaplama', 'route' => route('tools.distance')],
                    'road_distance' => ['label' => 'Karayolu Mesafe', 'route' => route('tools.road-distance')],
                    'cost' => ['label' => 'Tahmini Maliyet', 'route' => route('tools.cost')],
                    'checklist' => ['label' => 'Taşınma Kontrol Listesi', 'route' => route('tools.checklist')],
                    'moving_calendar' => ['label' => 'Taşınma Takvimi', 'route' => route('tools.moving-calendar')],
                ] as $slug => $info)
                <div class="admin-card p-5">
                    <h4 class="font-medium text-slate-800 dark:text-slate-200 mb-1">{{ $info['label'] }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">{{ $info['route'] }}</p>
                    <div class="space-y-3">
                        <div class="admin-form-group">
                            <label class="admin-label">Meta başlık</label>
                            <input type="text" name="tool_{{ $slug }}_meta_title" value="{{ old('tool_'.$slug.'_meta_title', $settings['tool_'.$slug.'_meta_title'] ?? '') }}" class="admin-input" placeholder="Örn: {{ $info['label'] }} - NakliyePark">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Meta açıklama (SEO)</label>
                            <textarea name="tool_{{ $slug }}_meta_description" rows="2" maxlength="500" class="admin-input text-sm">{{ old('tool_'.$slug.'_meta_description', $settings['tool_'.$slug.'_meta_description'] ?? '') }}</textarea>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-label">Nasıl çalışır? (HTML)</label>
                            <textarea name="tool_{{ $slug }}_content" rows="4" class="admin-input text-sm" placeholder="<p>Bu araç ile...</p>">{{ old('tool_'.$slug.'_content', $settings['tool_'.$slug.'_content'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
                <div class="mt-6">
                    <button type="submit" class="admin-btn-primary">Araç sayfaları kaydet</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tab 3: Mail gönderimi (gerçek eposta nasıl gidecek) --}}
    <div role="tabpanel" id="panel-mail" class="settings-tab-panel" aria-labelledby="tab-mail" hidden>
        <div class="grid lg:grid-cols-2 gap-6">
        <div class="admin-card p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="settings_section" value="mail">
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">E-posta nasıl gönderilsin?</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Sistemdeki tüm e-postalar (bildirimler, şablonlar) bu ayarla gönderilir.</p>
                    <div class="admin-form-group max-w-xs">
                        <label class="admin-label">Mail sürücüsü (Mailer)</label>
                        <select name="mail_mailer" class="admin-input">
                            <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (sunucu üzerinden)</option>
                            <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ ($settings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log (test – dosyaya yazar)</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">SMTP: Gerçek eposta gönderir. Log: Sadece test için, storage/logs'a yazar.</p>
                    </div>
                </div>
                <div class="border-b border-slate-200 dark:border-slate-600 pb-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Gönderici (From)</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Tüm e-postalarda görünecek gönderen adı ve adresi.</p>
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
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">SMTP sunucusu</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">Mail sürücüsü SMTP ise bu ayarlar kullanılır (Gmail, Yandex, kendi sunucunuz vb.).</p>
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
                <div class="pt-2">
                    <button type="submit" class="admin-btn-primary">Mail ayarlarını kaydet</button>
                </div>
            </form>
        </div>
        <div class="admin-card p-6">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Mail testi</h3>
            <form method="POST" action="{{ route('admin.settings.test-mail') }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="admin-form-group mb-0 flex-1 min-w-[200px]">
                    <label class="admin-label">Test e-postası gönderilecek adres</label>
                    <input type="email" name="test_email" value="{{ old('test_email', auth()->user()->email) }}" required class="admin-input" placeholder="test@example.com">
                    @error('test_email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="admin-btn-primary">Test maili gönder</button>
            </form>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Önce mail ayarlarını kaydedin, sonra test edin.</p>
        </div>
        </div>
    </div>

    {{-- Tab: Komisyon --}}
    <div role="tabpanel" id="panel-commission" class="settings-tab-panel" aria-labelledby="tab-commission" hidden>
        <div class="admin-card p-6 max-w-xl">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Komisyon oranı</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-5">Nakliye firmalarının kabul edilen tekliflerinden alınacak NakliyePark komisyon oranı.</p>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="settings_section" value="commission">
                <div class="admin-form-group max-w-xs">
                    <label class="admin-label">Komisyon oranı (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $settings['commission_rate'] ?? '10') }}" min="0" max="100" step="0.01" class="admin-input" placeholder="10">
                    @error('commission_rate')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Firmaların kabul edilen tekliflerinden alınacak oran.</p>
                </div>
                <button type="submit" class="admin-btn-primary">Komisyonu kaydet</button>
            </form>
        </div>
    </div>

    {{-- Tab 4: Stil & Scriptler --}}
    <div role="tabpanel" id="panel-style" class="settings-tab-panel" aria-labelledby="tab-style" hidden>
        <div class="admin-card p-6 w-full max-w-4xl">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Özel HTML / Scriptler</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Google Ads doğrulaması, Meta doğrulaması, Analytics veya diğer scriptleri ekleyin. Her alan frontende doğru yere yerleştirilir.</p>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="settings_section" value="style">
                <div class="admin-form-group">
                    <label class="admin-label">Header (&lt;head&gt; içine)</label>
                    <textarea name="custom_header_html" rows="4" class="admin-input font-mono text-sm w-full" placeholder="Örn: &lt;meta name=&quot;google-site-verification&quot; content=&quot;...&quot;&gt;">{{ old('custom_header_html', $settings['custom_header_html'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Meta doğrulama etiketleri, head içi scriptler.</p>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Footer (sayfa sonu, body içi)</label>
                    <textarea name="custom_footer_html" rows="4" class="admin-input font-mono text-sm w-full" placeholder="Örn: ek HTML blokları">{{ old('custom_footer_html', $settings['custom_footer_html'] ?? '') }}</textarea>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Scriptler (body sonu)</label>
                    <textarea name="custom_scripts" rows="6" class="admin-input font-mono text-sm w-full" placeholder="Örn: Google Analytics, Google Ads snippet">{{ old('custom_scripts', $settings['custom_scripts'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Google Analytics, reklam pixel vb. &lt;script&gt; etiketleri.</p>
                </div>
                <button type="submit" class="admin-btn-primary">Stil ve scriptler kaydet</button>
            </form>
        </div>
    </div>

    {{-- Tab: Mail şablonları — Projeden giden tüm sistem e-postaları --}}
    <div role="tabpanel" id="panel-mail-templates" class="settings-tab-panel" aria-labelledby="tab-mail-templates" hidden>
        <div class="admin-card p-6 w-full">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Sistem e-posta şablonları ve içerikleri</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Projeden otomatik giden <strong>tüm e-postalar</strong> burada tanımlıdır. Her biri bir kullanıcı etkileşiminde (ihale oluşturma, teklif gelmesi, şifre sıfırlama vb.) tetiklenir. <strong>Konu</strong> ve <strong>içerik (gövde)</strong> metnini özelleştirebilirsiniz; boş bırakırsanız varsayılan metin kullanılır.</p>
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">İçerikte kullanılabilecek değişkenler: <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{from_city}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{to_city}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{site_name}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{action_url}</code> (ilgili sayfa linki), <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{firma_adi}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{teklif_tutar}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{musteri_adi}</code>, <code class="text-xs bg-slate-100 dark:bg-slate-700 px-1 rounded">{reset_url}</code> (şifre sıfırlama). HTML kullanabilirsiniz (örn. <code class="text-xs">&lt;p&gt;</code>, <code class="text-xs">&lt;a&gt;</code>, <code class="text-xs">&lt;strong&gt;</code>).</p>

            <form method="POST" action="{{ route('admin.settings.update-mail-templates') }}">
                @csrf
                @php
                    $systemEmails = [
                        ['key' => 'admin_new_ihale',  'trigger' => 'Müşteri veya misafir nakliye talebi (ihale) oluşturduğunda',           'who' => 'Admin',      'label' => 'Yeni ihale bildirimi'],
                        ['key' => 'musteri_ihale_created',  'trigger' => 'Talep (ihale) sisteme kaydedildiğinde',                         'who' => 'Müşteri / Misafir e-postası', 'label' => 'İhale oluşturuldu'],
                        ['key' => 'musteri_ihale_published', 'trigger' => 'Admin ihale talebini onaylayıp yayına aldığında',               'who' => 'Müşteri / Misafir e-postası', 'label' => 'İhale onaylandı / yayında'],
                        ['key' => 'musteri_teklif_received', 'trigger' => 'Nakliyeci ihaleye teklif verdiğinde',                           'who' => 'Müşteri / Misafir e-postası', 'label' => 'Yeni teklif alındı'],
                        ['key' => 'nakliyeci_ihale_preferred','trigger' => 'Müşteri firmayı tercih etti ve admin ihale onayladığında',      'who' => 'Nakliyeci (firma)', 'label' => 'Tercih edilen ihale yayında'],
                        ['key' => 'nakliyeci_teklif_accepted','trigger' => 'Müşteri bir teklifi kabul ettiğinde',                           'who' => 'Nakliyeci (firma)', 'label' => 'Teklif kabul edildi'],
                        ['key' => 'nakliyeci_contact_message','trigger' => 'Müşteri kabul ettiği firmaya iletişim mesajı gönderdiğinde',    'who' => 'Nakliyeci (firma)', 'label' => 'Müşteri mesajı'],
                        ['key' => 'password_reset',          'trigger' => 'Kullanıcı "Şifremi unuttum" ile link talep ettiğinde',           'who' => 'Müşteri veya Nakliyeci', 'label' => 'Şifre sıfırlama'],
                    ];
                @endphp
                <div class="space-y-8">
                    @foreach($systemEmails as $row)
                    <div class="border border-slate-200 dark:border-slate-600 rounded-xl p-5 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $row['label'] }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">→ {{ $row['who'] }}</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ $row['trigger'] }}</p>
                        <div class="grid sm:grid-cols-1 gap-4">
                            <div class="admin-form-group">
                                <label class="admin-label">E-posta konusu</label>
                                <input type="text" name="mail_tpl_{{ $row['key'] }}_subject" value="{{ old('mail_tpl_'.$row['key'].'_subject', $settings['mail_tpl_'.$row['key'].'_subject'] ?? '') }}" class="admin-input text-sm w-full" placeholder="Boş bırakılırsa varsayılan konu kullanılır">
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-label">E-posta içeriği (gövde)</label>
                                <textarea name="mail_tpl_{{ $row['key'] }}_body" rows="5" class="admin-input text-sm w-full font-mono" placeholder="Boş bırakılırsa varsayılan metin kullanılır. HTML ve değişkenler kullanabilirsiniz.">{{ old('mail_tpl_'.$row['key'].'_body', $settings['mail_tpl_'.$row['key'].'_body'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="pt-6">
                    <button type="submit" class="admin-btn-primary">Mail şablonlarını kaydet</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tab: Paketler --}}
    <div role="tabpanel" id="panel-packages" class="settings-tab-panel" aria-labelledby="tab-packages" hidden>
        <div class="admin-card p-6 w-full max-w-4xl">
            <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Nakliyeci paketleri ve fiyatları</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Başlangıç, Profesyonel ve Kurumsal paketlerin adı, aylık fiyatı (₺) ve teklif limitini güncelleyebilirsiniz.</p>
            <form method="POST" action="{{ route('admin.settings.update-packages') }}" class="space-y-6">
                @csrf
                @foreach($settings['nakliyeci_paketler'] ?? [] as $p)
                    @php $id = $p['id'] ?? ''; @endphp
                    <div class="border border-slate-200 dark:border-slate-600 rounded-xl p-5">
                        <h4 class="font-medium text-slate-800 dark:text-slate-200 mb-3">{{ $p['name'] ?? $id }}</h4>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="admin-form-group">
                                <label class="admin-label">Paket adı</label>
                                <input type="text" name="paket_{{ $id }}_name" value="{{ old('paket_'.$id.'_name', $p['name'] ?? '') }}" class="admin-input">
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-label">Aylık fiyat (₺)</label>
                                <input type="number" name="paket_{{ $id }}_price" value="{{ old('paket_'.$id.'_price', $p['price'] ?? 0) }}" min="0" class="admin-input">
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-label">Aylık teklif limiti</label>
                                <input type="number" name="paket_{{ $id }}_teklif_limit" value="{{ old('paket_'.$id.'_teklif_limit', $p['teklif_limit'] ?? 50) }}" min="1" class="admin-input">
                            </div>
                        </div>
                        <div class="admin-form-group mt-3">
                            <label class="admin-label">Kısa açıklama</label>
                            <textarea name="paket_{{ $id }}_description" rows="2" class="admin-input text-sm">{{ old('paket_'.$id.'_description', $p['description'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="admin-btn-primary">Paketleri kaydet</button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var container = document.getElementById('settings-tabs');
    if (!container) return;
    var tabs = container.querySelectorAll('.settings-tab');
    var panels = container.querySelectorAll('.settings-tab-panel');
    var storageKey = 'admin_settings_tab';

    function activate(tabId) {
        tabs.forEach(function(t) {
            var isActive = t.getAttribute('data-tab') === tabId;
            t.setAttribute('aria-selected', isActive);
            t.classList.toggle('text-emerald-600', isActive);
            t.classList.toggle('dark:text-emerald-400', isActive);
            t.classList.toggle('border-emerald-500', isActive);
            t.classList.toggle('bg-white', isActive);
            t.classList.toggle('dark:bg-slate-800', isActive);
            t.classList.toggle('text-slate-600', !isActive);
            t.classList.toggle('dark:text-slate-400', !isActive);
            t.classList.toggle('border-transparent', !isActive);
        });
        panels.forEach(function(p) {
            var id = p.id ? p.id.replace('panel-', '') : '';
            var isActive = id === tabId;
            p.classList.toggle('active', isActive);
            p.hidden = !isActive;
        });
        try { localStorage.setItem(storageKey, tabId); } catch (e) {}
    }

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            activate(tab.getAttribute('data-tab'));
        });
    });

    var hash = window.location.hash.slice(1);
    var saved = null;
    try { saved = localStorage.getItem(storageKey); } catch (e) {}
    var initial = (hash === 'site' || hash === 'tools' || hash === 'mail' || hash === 'commission' || hash === 'style' || hash === 'mail-templates' || hash === 'packages') ? hash : (saved || 'site');
    activate(initial);
})();
</script>
@endsection
