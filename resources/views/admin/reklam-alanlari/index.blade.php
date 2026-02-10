@extends('layouts.admin')

@section('title', 'Reklam Alanları')
@section('page_heading', 'Reklam alanları')
@section('page_subtitle', 'Sayfalardaki reklam slotları: Google AdSense kodu, kendi görsel reklamlarınız. Blog, ihale, defter ve anasayfa için konum seçin.')

@section('content')
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
