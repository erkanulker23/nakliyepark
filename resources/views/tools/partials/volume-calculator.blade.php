@php
    $volumeRooms = $volumeRooms ?? config('volume_calculator.rooms');
    $volumeVehicles = $volumeVehicles ?? config('volume_calculator.vehicles');
    $roomIds = array_keys($volumeRooms);
@endphp
<div class="volume-calculator-widget rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-lg overflow-hidden" id="volume-calculator">
    <div class="p-4 sm:p-5 border-b border-zinc-200 dark:border-zinc-800 bg-gradient-to-r from-emerald-50/80 to-teal-50/50 dark:from-emerald-950/20 dark:to-zinc-900">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </span>
            Hacim Hesaplama
        </h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Eşyaya tıklayın veya <strong>+ / −</strong> ile adet ekleyip çıkarın; toplam hacim ve araç sayısı otomatik hesaplansın.</p>
    </div>

    {{-- Tabs --}}
    <div class="flex overflow-x-auto border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/80 dark:bg-zinc-800/50 scrollbar-hide" role="tablist" aria-label="Oda kategorileri">
        @foreach($volumeRooms as $roomKey => $room)
            <button type="button"
                    class="vol-tab shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg transition-colors whitespace-nowrap {{ $loop->first ? 'active' : '' }}"
                    data-room="{{ $roomKey }}"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    aria-controls="panel-{{ $roomKey }}"
                    id="tab-{{ $roomKey }}">
                {{ $room['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Panels --}}
    @foreach($volumeRooms as $roomKey => $room)
        <div class="vol-panel p-4 sm:p-5 {{ $loop->first ? '' : 'hidden' }}"
             id="panel-{{ $roomKey }}"
             role="tabpanel"
             aria-labelledby="tab-{{ $roomKey }}">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($room['items'] as $item)
                    <div class="vol-item-card flex flex-col items-center p-3 sm:p-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 hover:border-zinc-300 dark:hover:border-zinc-600 transition-all min-h-[100px] sm:min-h-[110px]"
                         data-id="{{ $roomKey }}__{{ $item['id'] }}"
                         data-min="{{ $item['min_m3'] }}"
                         data-max="{{ $item['max_m3'] }}"
                         title="{{ $item['name'] }} ({{ $item['min_m3'] }}–{{ $item['max_m3'] }} m³)">
                        <span class="text-2xl sm:text-3xl leading-none mb-1" aria-hidden="true">{{ $item['icon'] }}</span>
                        <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 line-clamp-2 text-center leading-tight mb-2">{{ $item['name'] }}</span>
                        <div class="flex items-center gap-1.5 mt-auto">
                            <button type="button" class="vol-btn-minus w-8 h-8 rounded-lg flex items-center justify-center bg-zinc-200 dark:bg-zinc-600 hover:bg-red-500 hover:text-white text-zinc-600 dark:text-zinc-300 font-bold text-lg leading-none transition-colors" aria-label="Bir azalt">−</button>
                            <span class="vol-item-count min-w-[2rem] text-center text-sm font-bold text-zinc-900 dark:text-white tabular-nums">0</span>
                            <button type="button" class="vol-btn-plus w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-lg leading-none transition-colors" aria-label="Bir ekle">+</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Özet + Araç doluluk (kamyon → kamyonet → tır → panelvan) --}}
    <div class="p-4 sm:p-5 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 space-y-4">
        <div class="flex flex-wrap items-center gap-4 sm:gap-6">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Seçilen eşya adedi:</span>
                <span id="vol-total-count" class="text-lg font-bold text-zinc-900 dark:text-white tabular-nums">0</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Hacim m³:</span>
                <span class="flex items-center gap-1.5">
                    <input type="text" id="vol-display-min" readonly class="w-16 text-center py-1.5 rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 text-sm font-semibold tabular-nums" value="0" aria-label="Minimum hacim">
                    <span class="text-zinc-400">–</span>
                    <input type="text" id="vol-display-max" readonly class="w-16 text-center py-1.5 rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 text-sm font-semibold tabular-nums" value="0" aria-label="Maksimum hacim">
                </span>
            </div>
            <button type="button" id="vol-reset" class="px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold transition-colors">
                Hesabı Sıfırla
            </button>
        </div>
        <div>
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-2">Eşyaya göre araç doluluk (önce kamyon, sonra kamyonet, tır, panelvan):</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl" aria-hidden="true">🚛</span>
                    <div class="min-w-0">
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 block">Kamyon</span>
                        <span id="vol-n-kamyon" class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">—</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">(~50 m³)</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl" aria-hidden="true">🚚</span>
                    <div class="min-w-0">
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 block">Kamyonet</span>
                        <span id="vol-n-kamyonet" class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">—</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">(~18 m³)</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl" aria-hidden="true">🚛</span>
                    <div class="min-w-0">
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 block">Tır</span>
                        <span id="vol-n-tir" class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">—</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">(~90 m³)</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl" aria-hidden="true">🚐</span>
                    <div class="min-w-0">
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 block">Panelvan</span>
                        <span id="vol-n-panelvan" class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">—</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">(~12 m³)</span>
                    </div>
                </div>
            </div>
            <p id="vol-remainder" class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 hidden">Son araçta <span id="vol-remainder-m3" class="font-medium text-zinc-700 dark:text-zinc-300">0</span> m³ doluluk.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const rooms = @json($volumeRooms);
    const KAMYON_M3 = 50;
    const KAMYONET_M3 = 18;
    const TIR_M3 = 90;
    const PANELVAN_M3 = 12;

    const state = {};
    const widget = document.getElementById('volume-calculator');
    if (!widget) return;

    const totalCountEl = document.getElementById('vol-total-count');
    const displayMinEl = document.getElementById('vol-display-min');
    const displayMaxEl = document.getElementById('vol-display-max');
    const volNKamyon = document.getElementById('vol-n-kamyon');
    const volNKamyonet = document.getElementById('vol-n-kamyonet');
    const volNTir = document.getElementById('vol-n-tir');
    const volNPanelvan = document.getElementById('vol-n-panelvan');
    const remainderEl = document.getElementById('vol-remainder');
    const remainderM3El = document.getElementById('vol-remainder-m3');
    const resetBtn = document.getElementById('vol-reset');

    function updateTotals() {
        let count = 0, minSum = 0, maxSum = 0;
        for (const key in state) {
            const n = state[key];
            if (n <= 0) continue;
            const parts = key.split('__');
            const roomKey = parts[0];
            const itemId = parts.slice(1).join('__');
            const room = rooms[roomKey];
            if (!room || !room.items) continue;
            const item = room.items.find(function(i) { return i.id === itemId; });
            if (!item) continue;
            count += n;
            minSum += n * parseFloat(item.min_m3);
            maxSum += n * parseFloat(item.max_m3);
        }
        if (totalCountEl) totalCountEl.textContent = count;
        if (displayMinEl) displayMinEl.value = minSum.toFixed(2);
        if (displayMaxEl) displayMaxEl.value = maxSum.toFixed(2);

        var total = (minSum + maxSum) / 2;
        var remaining = total;
        var nKamyon = Math.floor(remaining / KAMYON_M3);
        remaining -= nKamyon * KAMYON_M3;
        var nKamyonet = Math.floor(remaining / KAMYONET_M3);
        remaining -= nKamyonet * KAMYONET_M3;
        var nTir = Math.floor(remaining / TIR_M3);
        remaining -= nTir * TIR_M3;
        var nPanelvanFull = Math.floor(remaining / PANELVAN_M3);
        var lastM3 = remaining - nPanelvanFull * PANELVAN_M3;
        var nPanelvan = nPanelvanFull + (lastM3 > 0 ? 1 : 0);

        function showCount(n, el) {
            if (el) el.textContent = n > 0 ? (n === 1 ? '1 araç' : n + ' araç') : '—';
        }
        showCount(nKamyon, volNKamyon);
        showCount(nKamyonet, volNKamyonet);
        showCount(nTir, volNTir);
        showCount(nPanelvan, volNPanelvan);

        if (remainderEl && remainderM3El) {
            if (nPanelvan > 0 && lastM3 > 0) {
                remainderM3El.textContent = lastM3.toFixed(1);
                remainderEl.classList.remove('hidden');
            } else {
                remainderEl.classList.add('hidden');
            }
        }
    }

    function updateBadges() {
        widget.querySelectorAll('.vol-item-card').forEach(function(card) {
            const key = card.dataset.id;
            if (!key) return;
            const n = state[key] || 0;
            const countEl = card.querySelector('.vol-item-count');
            const minusBtn = card.querySelector('.vol-btn-minus');
            if (countEl) countEl.textContent = n;
            if (minusBtn) {
                minusBtn.disabled = n === 0;
                minusBtn.classList.toggle('opacity-50', n === 0);
                minusBtn.classList.toggle('cursor-not-allowed', n === 0);
            }
        });
    }

    widget.querySelectorAll('.vol-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            const room = this.getAttribute('data-room');
            widget.querySelectorAll('.vol-tab').forEach(function(t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            widget.querySelectorAll('.vol-panel').forEach(function(p) {
                p.classList.add('hidden');
            });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            const panel = document.getElementById('panel-' + room);
            if (panel) panel.classList.remove('hidden');
        });
    });

    widget.addEventListener('click', function(e) {
        var plus = e.target.closest('.vol-btn-plus');
        var minus = e.target.closest('.vol-btn-minus');
        var card = plus ? plus.closest('.vol-item-card') : (minus ? minus.closest('.vol-item-card') : null);
        if (!card) return;
        var key = card.getAttribute('data-id');
        if (!key) return;
        e.preventDefault();
        e.stopPropagation();
        if (plus) {
            state[key] = (state[key] || 0) + 1;
        } else if (minus) {
            state[key] = Math.max(0, (state[key] || 0) - 1);
        }
        updateBadges();
        updateTotals();
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            for (const k in state) state[k] = 0;
            updateBadges();
            updateTotals();
        });
    }

    updateTotals();
    updateBadges();
})();
</script>
@endpush

@push('styles')
<style>
.vol-tab { color: #71717a; }
.vol-tab:hover { color: #3f3f46; }
.dark .vol-tab { color: #a1a1aa; }
.dark .vol-tab:hover { color: #e4e4e7; }
.vol-tab.active { color: #059669; background: rgba(255,255,255,.8); border-bottom: 2px solid #059669; }
.dark .vol-tab.active { color: #34d399; background: rgba(24,24,27,.5); border-bottom-color: #34d399; }
</style>
@endpush
