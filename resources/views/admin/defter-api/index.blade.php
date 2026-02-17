@extends('layouts.admin')

@section('title', 'Defter API')
@section('page_heading', 'Defter API')

@section('content')
<p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
    Harici sarı defter (evdennakliyateve.com vb.) API’sinden veri çekip burada listelersiniz. İstediğiniz kayıtları <strong>firma olarak sisteme ekleyebilirsiniz</strong>; eklenen firmalar <strong>onay bekleyen</strong> olarak oluşturulur, admin onayından sonra yayına alınır.
</p>

{{-- API ayarları: URL ve cookie bu sayfadan girilir --}}
<div class="admin-card p-6 rounded-2xl mb-6 border border-slate-200 dark:border-slate-700">
    <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">API ayarları</h2>
    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
        Veri çekmek için aşağıya Defter API adresini (defter-api.php’nin tam URL’si) yazıp <strong>Kaydet</strong>’e tıklayın. Cookie isteğe bağlıdır; oturum açık veri için gerekebilir. Cookie süresi dolunca tarayıcıda <strong>F12 → Console</strong> açıp <code class="px-1 py-0.5 bg-slate-200 dark:bg-slate-700 rounded text-xs">document.cookie</code> yazarak çıkan değeri kopyalayıp aşağıdaki alana yapıştırın.
    </p>
    <form method="post" action="{{ route('admin.defter-api.settings') }}" class="space-y-4 max-w-2xl">
        @csrf
        <div>
            <label for="defter_api_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">API adresi (URL) *</label>
            <input type="url" id="defter_api_url" name="defter_api_url" value="{{ old('defter_api_url', $settings['url']) }}" placeholder="https://example.com/defter-api.php" class="admin-input w-full py-2 text-sm">
            @error('defter_api_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="defter_api_cookie" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cookie (isteğe bağlı)</label>
            <textarea id="defter_api_cookie" name="defter_api_cookie" rows="3" placeholder="PHPSESSID=...; remember_token=..." class="admin-input w-full py-2 text-sm resize-y">{{ old('defter_api_cookie', $settings['cookie']) }}</textarea>
            @error('defter_api_cookie')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="defter_api_fetch_limit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Çekilecek maksimum kayıt</label>
            <input type="number" id="defter_api_fetch_limit" name="defter_api_fetch_limit" value="{{ old('defter_api_fetch_limit', $settings['fetch_limit']) }}" min="10" max="5000" step="1" class="admin-input w-32 py-2 text-sm">
            <span class="text-slate-500 text-sm ml-2">(varsayılan 500)</span>
            @error('defter_api_fetch_limit')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="admin-btn-primary text-sm py-2 px-4">Kaydet</button>
    </form>
</div>

@if(session('success'))
    <div class="admin-card p-4 rounded-xl mb-6 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="admin-card p-4 rounded-xl mb-6 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200">
        {{ session('error') }}
    </div>
@endif

<div class="flex flex-wrap items-center gap-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="text-sm text-slate-600 dark:text-slate-400">Toplam kayıt: <strong class="text-slate-800 dark:text-slate-200">{{ $stats['total'] }}</strong></span>
        <span class="text-slate-400">|</span>
        <span class="text-sm text-slate-600 dark:text-slate-400">Aktarılan: <strong class="text-emerald-600 dark:text-emerald-400">{{ $stats['imported'] }}</strong></span>
        <span class="text-slate-400">|</span>
        <span class="text-sm text-slate-600 dark:text-slate-400">Aktarılmayı bekleyen: <strong class="text-amber-600 dark:text-amber-400">{{ $stats['not_imported'] }}</strong></span>
    </div>
    @if($apiConfigured)
    <form method="post" action="{{ route('admin.defter-api.fetch') }}" class="inline">
        @csrf
        <button type="submit" class="admin-btn-primary text-sm py-2 px-4">Defter API’den veri çek</button>
    </form>
    @endif
</div>

<form method="get" action="{{ route('admin.defter-api.index') }}" class="flex flex-wrap items-center gap-2 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma veya içerik ara..." class="admin-input py-2 w-56 text-sm">
    <select name="imported" class="admin-input py-2 w-44 text-sm">
        <option value="">Tümü</option>
        <option value="0" {{ request('imported') === '0' ? 'selected' : '' }}>Aktarılmamış</option>
        <option value="1" {{ request('imported') === '1' ? 'selected' : '' }}>Aktarılmış</option>
    </select>
    <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele</button>
    @if(request()->hasAny(['q','imported']))
        <a href="{{ route('admin.defter-api.index') }}" class="admin-btn-secondary text-sm py-2">Temizle</a>
    @endif
</form>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th class="w-10"><input type="checkbox" id="select-all-import" title="Aktarılmamışları toplu seç"></th>
                <th>Firma / Kaynak ID</th>
                <th>İletişim</th>
                <th>İçerik</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entries as $e)
                <tr>
                    <td>
                        @if(!$e->company_id && trim($e->firma ?? '') !== '')
                            <input type="checkbox" name="ids[]" form="form-import-selected" value="{{ $e->id }}" class="row-import-check">
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $e->firma ?: '—' }}</span>
                        <br><span class="text-slate-500 text-xs">ID: {{ $e->external_id }}</span>
                    </td>
                    <td class="text-sm">
                        @if($e->phone)<span class="block">{{ $e->phone }}</span>@endif
                        @if($e->whatsapp)<span class="block text-emerald-600 dark:text-emerald-400">WA: {{ Str::limit($e->whatsapp, 25) }}</span>@endif
                        @if($e->email)<span class="block text-slate-500">{{ Str::limit($e->email, 30) }}</span>@endif
                        @if($e->giris_gerekli && $e->telefon_maskelenmis)<span class="block text-amber-600">{{ $e->telefon_maskelenmis }}</span>@endif
                        @if(!$e->phone && !$e->whatsapp && !$e->email && !$e->telefon_maskelenmis)—@endif
                    </td>
                    <td class="text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate" title="{{ $e->icerik }}">{{ Str::limit($e->icerik, 80) ?: '—' }}</td>
                    <td>
                        @if($e->company_id)
                            <a href="{{ route('admin.companies.edit', $e->company) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm">Firma sayfası →</a>
                            @if($e->company->approved_at)
                                <span class="block text-xs text-slate-500">Onaylı</span>
                            @else
                                <span class="block text-xs text-amber-600 dark:text-amber-400">Onay bekliyor</span>
                            @endif
                        @else
                            <span class="text-slate-500 text-sm">Aktarılmadı</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if(!$e->company_id && trim($e->firma ?? '') !== '')
                            <form method="post" action="{{ route('admin.defter-api.import', $e) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm py-1.5 px-3 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Firmaya dönüştür</button>
                            </form>
                        @elseif($e->company_id)
                            <a href="{{ route('admin.companies.edit', $e->company) }}" class="admin-btn-secondary text-sm py-1.5 px-3">Düzenle</a>
                        @else
                            <span class="text-slate-400 text-sm">Firma adı yok</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500 dark:text-slate-400">
                        Henüz kayıt yok. API URL’yi ayarlayıp <strong>Defter API’den veri çek</strong> ile verileri alın.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($entries->hasPages())
    <div class="mt-4">
        {{ $entries->links() }}
    </div>
@endif

@if($stats['not_imported'] > 0)
<form id="form-import-selected" method="post" action="{{ route('admin.defter-api.import-selected') }}" class="mt-6 flex items-center gap-3">
    @csrf
    <button type="submit" id="btn-import-selected" class="admin-btn-primary text-sm py-2 px-4" disabled>Seçilenleri firmaya dönüştür</button>
    <span class="text-sm text-slate-500" id="selected-count">0 kayıt seçildi</span>
</form>
@endif

@push('scripts')
<script>
(function() {
    var selectAll = document.getElementById('select-all-import');
    var form = document.getElementById('form-import-selected');
    var btn = document.getElementById('btn-import-selected');
    var countSpan = document.getElementById('selected-count');
    if (!form) return;

    function updateCount() {
        var checks = form.querySelectorAll('.row-import-check:checked');
        var n = checks.length;
        if (countSpan) countSpan.textContent = n + ' kayıt seçildi';
        if (btn) btn.disabled = n === 0;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            form.querySelectorAll('.row-import-check').forEach(function(cb) { cb.checked = selectAll.checked; });
            updateCount();
        });
    }
    form.querySelectorAll('.row-import-check').forEach(function(cb) {
        cb.addEventListener('change', updateCount);
    });
    updateCount();
})();
</script>
@endpush
@endsection
