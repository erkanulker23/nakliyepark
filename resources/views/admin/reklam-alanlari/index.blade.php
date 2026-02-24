@extends('layouts.admin')

@section('title', 'Reklam Alanları')
@section('page_heading', 'Reklam alanları')
@section('page_subtitle', 'Sayfalardaki reklam slotları: Google AdSense kodu, kendi görsel reklamlarınız. Blog, ihale, defter ve anasayfa için konum seçin.')

@section('content')
{{-- Google AdSense genel ayarları --}}
<div class="mb-8 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
        <h2 class="text-base font-semibold text-slate-800 dark:text-slate-200">Google AdSense bilgileri</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Site genelinde kullanılacak AdSense kod snippet'i, ads.txt içeriği ve meta etiket. Kaydettiğinizde tüm sayfalarda etkin olur.</p>
    </div>
    <form method="POST" action="{{ route('admin.reklam-alanlari.adsense-update') }}" class="p-4 sm:p-5 space-y-4">
        @csrf
        <div>
            <label for="adsense_code_snippet" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">AdSense kod snippet'i</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Google AdSense’den aldığınız &lt;script&gt;...&lt;/script&gt; kodunu buraya yapıştırın. &lt;head&gt; içine eklenir.</p>
            <textarea name="adsense_code_snippet" id="adsense_code_snippet" rows="6" class="admin-input w-full font-mono text-sm" placeholder="<script async src=\"https://pagead2.googlesyndication.com/...\"></script>">{{ old('adsense_code_snippet', $adsense['adsense_code_snippet'] ?? '') }}</textarea>
        </div>
        <div>
            <label for="ads_txt_content" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ads.txt snippet'i</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Google’dan aldığınız ads.txt satırlarını yapıştırın. Site kökünde <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-xs">{{ url('/ads.txt') }}</code> adresinden yayınlanır.</p>
            <textarea name="ads_txt_content" id="ads_txt_content" rows="5" class="admin-input w-full font-mono text-sm" placeholder="google.com, pub-XXXXXXXXXX, DIRECT, XXXXXXX">{{ old('ads_txt_content', $adsense['ads_txt_content'] ?? '') }}</textarea>
        </div>
        <div>
            <label for="adsense_meta_tag" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Meta etiket</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">AdSense doğrulama veya ek meta etiket (tam satır, örn. <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-xs">&lt;meta name="google-site-verification" content="..."&gt;</code>). &lt;head&gt; içine eklenir.</p>
            <textarea name="adsense_meta_tag" id="adsense_meta_tag" rows="2" class="admin-input w-full font-mono text-sm" placeholder='<meta name="google-site-verification" content="...">'>{{ old('adsense_meta_tag', $adsense['adsense_meta_tag'] ?? '') }}</textarea>
        </div>
        <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium text-sm">AdSense ayarlarını kaydet</button>
    </form>
</div>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap items-center gap-3">
        <form method="get" action="{{ route('admin.reklam-alanlari.index') }}" class="flex flex-wrap items-center gap-2">
            <select name="sayfa" class="admin-input py-2 text-sm w-auto" onchange="this.form.submit()">
                <option value="">Tüm sayfalar</option>
                @foreach(\App\Models\AdZone::sayfaSecenekleri() as $key => $label)
                    <option value="{{ $key }}" {{ request('sayfa') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="konum" class="admin-input py-2 text-sm w-auto" onchange="this.form.submit()">
                <option value="">Tüm konumlar</option>
                @foreach(\App\Models\AdZone::konumSecenekleri() as $key => $label)
                    <option value="{{ $key }}" {{ request('konum') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="tip" class="admin-input py-2 text-sm w-auto" onchange="this.form.submit()">
                <option value="">Tüm tipler</option>
                <option value="code" {{ request('tip') === 'code' ? 'selected' : '' }}>Kod (AdSense/HTML)</option>
                <option value="image" {{ request('tip') === 'image' ? 'selected' : '' }}>Görsel reklam</option>
            </select>
        </form>
    </div>
    <a href="{{ route('admin.reklam-alanlari.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium text-sm shrink-0">Yeni reklam alanı</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300 w-12">Sıra</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Sayfa / Konum</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Tip</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Başlık</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700 dark:text-slate-300">Durum</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700 dark:text-slate-300">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reklamlar as $r)
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-4 py-3 text-slate-500">{{ $r->sira }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium">{{ \App\Models\AdZone::sayfaSecenekleri()[$r->sayfa] ?? $r->sayfa }}</span>
                            <span class="text-slate-500 text-xs ml-1">→ {{ \App\Models\AdZone::konumSecenekleri()[$r->konum] ?? $r->konum }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($r->tip === 'code')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200">Kod</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-100 dark:bg-sky-900/40 text-sky-800 dark:text-sky-200">Görsel</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $r->baslik ?: '—' }}</td>
                        <td class="px-4 py-3">
                            @if($r->aktif)
                                <span class="text-emerald-600 dark:text-emerald-400">Aktif</span>
                            @else
                                <span class="text-slate-400">Pasif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.reklam-alanlari.edit', $r) }}" class="text-sky-500 hover:underline mr-2">Düzenle</a>
                            <form method="POST" action="{{ route('admin.reklam-alanlari.destroy', $r) }}" class="inline" onsubmit="return confirm('Bu reklam alanını silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            Henüz reklam alanı yok. <a href="{{ route('admin.reklam-alanlari.create') }}" class="text-sky-500 hover:underline">İlk reklam alanını ekleyin</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reklamlar->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $reklamlar->links() }}</div>
    @endif
</div>

<div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400">
    <p class="font-medium text-slate-700 dark:text-slate-300 mb-1">Kullanım:</p>
    <ul class="list-disc list-inside space-y-0.5">
        <li><strong>Kod:</strong> Google AdSense veya herhangi bir HTML/script reklam kodunu yapıştırın.</li>
        <li><strong>Görsel:</strong> Reklam görseli URL’si ve tıklanınca gidilecek link. Kendi resimlerinizi kullanabilirsiniz.</li>
        <li>Sayfa ve konum seçerek reklamın nerede görüneceğini belirleyin (blog, ihale listesi, ihale detay, defter, anasayfa).</li>
    </ul>
</div>
@endsection
