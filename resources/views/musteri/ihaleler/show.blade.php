@extends('layouts.musteri')

@section('title', 'İhale detay - ' . $ihale->from_location_text . ' → ' . $ihale->to_location_text)
@section('page_heading', 'İhale detayı')
@section('page_subtitle', $ihale->from_location_text . ' → ' . $ihale->to_location_text)

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- İhale özeti kartı --}}
    <div class="panel-card overflow-hidden mb-6">
        <div class="p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--panel-text)] tracking-tight">
                        {{ $ihale->from_location_text }} <span class="text-[var(--panel-text-muted)] font-normal">→</span> {{ $ihale->to_location_text }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-[var(--panel-text-muted)]">
                        <span>{{ $ihale->volume_m3 }} m³</span>
                        @if($ihale->move_date || $ihale->move_date_end)
                            <span>·</span>
                            @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
                                <span>{{ $ihale->move_date?->format('d.m.Y') }} – {{ $ihale->move_date_end?->format('d.m.Y') }}</span>
                            @else
                                <span>{{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') }}</span>
                            @endif
                        @else
                            <span>Fiyat bakıyorum</span>
                        @endif
                    </div>
                </div>
                <x-panel.status-badge :status="$ihale->status === 'pending' ? 'pending' : ($ihale->status === 'published' ? 'neutral' : ($ihale->status === 'draft' ? 'pending' : 'approved'))">
                    {{ $ihale->status === 'pending' ? 'Onay bekliyor' : ($ihale->status === 'published' ? 'Yayında' : ($ihale->status === 'closed' ? 'Kapalı' : ($ihale->status === 'draft' ? 'Beklemede' : 'Taslak'))) }}
                </x-panel.status-badge>
            </div>
            @if($ihale->description)
                <p class="text-sm text-[var(--panel-text-muted)] mt-4 whitespace-pre-line leading-relaxed">{{ $ihale->description }}</p>
            @endif
            @php
                $canClose = $ihale->status === 'published' && !$acceptedTeklif;
                $canOpen = in_array($ihale->status, ['closed', 'draft'], true) && !$acceptedTeklif;
                $canPause = $ihale->status === 'published' && !$acceptedTeklif;
            @endphp
            @if($canClose || $canOpen || $canPause)
                <div class="mt-4 pt-4 border-t border-[var(--panel-border)] flex flex-wrap items-center gap-2">
                    @if($canClose)
                        <form method="POST" action="{{ route('musteri.ihaleler.close', $ihale) }}" class="inline" onsubmit="return confirm('İhaleyi kapatmak istediğinize emin misiniz? Bekleyen teklifler reddedilir.');">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg border border-[var(--panel-border)] text-[var(--panel-text-muted)] hover:bg-[var(--panel-bg)]">İhaleyi kapat</button>
                        </form>
                    @endif
                    @if($canPause)
                        <form method="POST" action="{{ route('musteri.ihaleler.pause', $ihale) }}" class="inline" onsubmit="return confirm('İhaleyi bekleme moduna almak istiyor musunuz?');">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg border border-[var(--panel-border)] text-[var(--panel-text-muted)] hover:bg-[var(--panel-bg)]">Bekleme moduna al</button>
                        </form>
                    @endif
                    @if($canOpen)
                        <form method="POST" action="{{ route('musteri.ihaleler.open', $ihale) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg bg-[var(--panel-primary)] text-white hover:opacity-90">Yayına al</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Kabul edilen teklif + mesajlaşma --}}
    @if($acceptedTeklif)
        <div class="panel-card overflow-hidden mb-6">
            <div class="p-5 sm:p-6 border-b border-[var(--panel-border)] bg-[var(--panel-primary-soft)]/30 dark:bg-[var(--panel-primary-soft)]/20">
                <p class="text-xs font-semibold uppercase tracking-wider text-[var(--panel-text-muted)] mb-1">Kabul ettiğiniz teklif</p>
                <p class="text-2xl font-bold text-[var(--panel-primary)]">{{ number_format($acceptedTeklif->amount, 0, ',', '.') }} ₺</p>
                <p class="text-sm font-medium text-[var(--panel-text)] mt-1">{{ $acceptedTeklif->company->name ?? '-' }}</p>
                <div class="flex flex-wrap gap-3 mt-3">
                    @if($acceptedTeklif->company && $acceptedTeklif->company->phone)
                        <a href="tel:{{ $acceptedTeklif->company->phone }}" class="inline-flex items-center gap-1.5 text-sm text-[var(--panel-primary)] hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Ara
                        </a>
                    @endif
                    @if($acceptedTeklif->company && $acceptedTeklif->company->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $acceptedTeklif->company->whatsapp) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-[var(--panel-primary)] hover:underline">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                </div>
                @if($acceptedTeklif->canUndoAccept())
                    <form method="POST" action="{{ route('musteri.ihaleler.undo-accept', [$ihale, $acceptedTeklif]) }}" class="mt-4 inline-block" onsubmit="return confirm('Teklif kabulünü geri almak istediğinize emin misiniz? İhale tekrar yayına döner.');">
                        @csrf
                        <button type="submit" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Kabulü geri al ({{ \App\Models\Teklif::ACCEPT_UNDO_MINUTES }} dk içinde)</button>
                    </form>
                @endif
            </div>

            {{-- Mesajlaşma: sohbet görünümü --}}
            <div class="ihale-chat">
                <div class="ihale-chat__header px-5 py-3 border-b border-[var(--panel-border)]">
                    <h2 class="text-sm font-semibold text-[var(--panel-text)] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $acceptedTeklif->company->name ?? 'Firma' }} ile mesajlaşma
                    </h2>
                </div>
                <div class="ihale-chat__messages px-4 py-4 min-h-[200px] max-h-[360px] overflow-y-auto bg-[var(--panel-bg)]/50" id="chat-messages">
                    @forelse($acceptedTeklif->contactMessages as $msg)
                        @php $isMe = $msg->from_user_id === auth()->id(); @endphp
                        <div class="ihale-chat__row flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-3">
                            <div class="ihale-chat__bubble max-w-[85%] sm:max-w-[75%] {{ $isMe ? 'ihale-chat__bubble--me' : 'ihale-chat__bubble--them' }}">
                                @if(!$isMe)
                                    <p class="ihale-chat__sender text-xs font-medium text-[var(--panel-text-muted)] mb-1">{{ $msg->fromUser->name ?? $acceptedTeklif->company->name ?? 'Firma' }}</p>
                                @endif
                                <p class="ihale-chat__text text-sm whitespace-pre-wrap break-words">{{ $msg->message }}</p>
                                <p class="ihale-chat__time text-[10px] text-[var(--panel-text-muted)] mt-1.5 {{ $isMe ? 'text-right' : 'text-left' }}">{{ $msg->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[var(--panel-text-muted)] text-center py-8">Henüz mesaj yok. Aşağıdan ilk mesajınızı yazın.</p>
                    @endforelse
                </div>
                <div class="ihale-chat__compose p-4 border-t border-[var(--panel-border)] bg-[var(--panel-surface)]">
                    <form action="{{ route('musteri.ihaleler.contact-message', [$ihale, $acceptedTeklif]) }}" method="POST" class="flex gap-2" id="chat-form">
                        @csrf
                        <textarea name="message" rows="2" class="ihale-chat__input flex-1 min-h-[44px] max-h-[120px] px-4 py-3 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-bg)] text-sm text-[var(--panel-text)] placeholder-[var(--panel-text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--panel-primary)]/30 focus:border-[var(--panel-primary)] resize-none" placeholder="Taşınma tarihi, sorularınız veya notlarınızı yazın…" required maxlength="2000"></textarea>
                        <button type="submit" class="ihale-chat__send shrink-0 w-12 h-12 rounded-2xl bg-[var(--panel-primary)] text-white flex items-center justify-center hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[var(--panel-primary)]/50" aria-label="Gönder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-5 border-t border-[var(--panel-border)] space-y-4">
                <a href="{{ route('review.create', $ihale) }}" class="inline-block text-sm font-medium text-[var(--panel-primary)] hover:underline">Değerlendirme yap →</a>
                <div class="pt-2 border-t border-[var(--panel-border)]">
                    <p class="text-sm font-medium text-[var(--panel-text)] mb-2">Sorun mu yaşadınız?</p>
                    <form action="{{ route('musteri.ihaleler.dispute.store', $ihale) }}" method="POST" class="space-y-2 max-w-md">
                        @csrf
                        <select name="reason" class="w-full text-sm rounded-xl border border-[var(--panel-border)] bg-[var(--panel-surface)] text-[var(--panel-text)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--panel-primary)]/30" required>
                            <option value="">Sebep seçin</option>
                            @foreach(\App\Models\Dispute::reasonLabels() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="description" rows="2" class="w-full text-sm rounded-xl border border-[var(--panel-border)] bg-[var(--panel-surface)] text-[var(--panel-text)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--panel-primary)]/30" placeholder="Kısa açıklama (isteğe bağlı)" maxlength="2000"></textarea>
                        <button type="submit" class="text-sm py-2 px-4 rounded-xl border border-[var(--panel-border)] text-[var(--panel-text-muted)] hover:bg-[var(--panel-bg)]">Şikâyet / uyuşmazlık aç</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Gelen teklifler listesi --}}
    <div class="panel-card p-5 sm:p-6">
        <h2 class="text-lg font-semibold text-[var(--panel-text)] mb-4">Gelen teklifler ({{ $ihale->teklifler->count() }})</h2>
        <ul class="space-y-4">
            @forelse($ihale->teklifler as $t)
                <li class="flex flex-wrap items-start justify-between gap-4 py-4 border-b border-[var(--panel-border)] last:border-0">
                    <div class="min-w-0">
                        <p class="font-medium text-[var(--panel-text)]">{{ $t->company->name ?? '-' }}</p>
                        <p class="text-xl font-bold text-[var(--panel-text)] mt-0.5">{{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                        @if($t->message)
                            <p class="text-sm text-[var(--panel-text-muted)] mt-2 line-clamp-2">{{ $t->message }}</p>
                        @endif
                        <p class="text-xs text-[var(--panel-text-muted)] mt-2">{{ $t->created_at->format('d.m.Y H:i') }}</p>
                        @if($t->status === 'rejected' && $t->reject_reason)
                            <p class="text-xs text-[var(--panel-text-muted)] mt-2"><span class="font-medium">Red gerekçesi:</span> {{ $t->reject_reason }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if($t->status === 'accepted')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Kabul edildi</span>
                        @elseif($t->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Reddedildi</span>
                        @elseif(!$acceptedTeklif && $ihale->status === 'published')
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('musteri.ihaleler.accept-teklif', [$ihale, $t]) }}" class="inline" onsubmit="return confirm('Bu teklifi kabul etmek istediğinize emin misiniz? Diğer teklifler reddedilir.');">
                                    @csrf
                                    <button type="submit" class="admin-btn-primary text-sm py-2 px-4 rounded-xl">Teklifi kabul et</button>
                                </form>
                                <button type="button" class="reject-teklif-toggle admin-btn-secondary text-sm py-2 px-4 rounded-xl" data-teklif-id="{{ $t->id }}" aria-expanded="false">Reddet</button>
                            </div>
                            <div id="reject-form-{{ $t->id }}" class="reject-form w-full max-w-md mt-2 {{ session('reject_teklif_id') == $t->id ? '' : 'hidden' }}">
                                <form method="POST" action="{{ route('musteri.ihaleler.reject-teklif', [$ihale, $t]) }}" class="p-4 rounded-xl bg-[var(--panel-bg)] border border-[var(--panel-border)] space-y-2">
                                    @csrf
                                    <label for="reject_reason_{{ $t->id }}" class="block text-sm font-medium text-[var(--panel-text)]">Red gerekçesi (isteğe bağlı)</label>
                                    <textarea id="reject_reason_{{ $t->id }}" name="reject_reason" rows="2" class="w-full text-sm rounded-xl border border-[var(--panel-border)] bg-[var(--panel-surface)] px-3 py-2 text-[var(--panel-text)] focus:outline-none focus:ring-2 focus:ring-[var(--panel-primary)]/30" placeholder="Neden reddediyorsunuz? (opsiyonel)" maxlength="1000">{{ old('reject_reason') }}</textarea>
                                    @error('reject_reason')
                                        <p class="text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <div class="flex gap-2">
                                        <button type="submit" class="text-sm py-2 px-3 rounded-xl bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/60">Reddet</button>
                                        <button type="button" class="reject-teklif-cancel admin-btn-secondary text-sm py-2 px-3 rounded-xl" data-teklif-id="{{ $t->id }}">İptal</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Beklemede</span>
                        @endif
                    </div>
                </li>
            @empty
                <li class="py-12 text-center text-[var(--panel-text-muted)]">Henüz teklif gelmedi.</li>
            @endforelse
        </ul>
    </div>
</div>

@push('styles')
<style>
/* Mesajlaşma: sohbet balonları */
.ihale-chat__bubble {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    word-break: break-word;
}
.ihale-chat__bubble--me {
    background: var(--panel-primary);
    color: #fff;
    border-bottom-right-radius: 0.25rem;
}
.ihale-chat__bubble--them {
    background: var(--panel-surface);
    border: 1px solid var(--panel-border);
    color: var(--panel-text);
    border-bottom-left-radius: 0.25rem;
}
.ihale-chat__bubble--me .ihale-chat__time { color: rgba(255,255,255,0.85); }
.ihale-chat__input::placeholder { color: var(--panel-text-muted); }
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.reject-teklif-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-teklif-id');
        var form = document.getElementById('reject-form-' + id);
        if (!form) return;
        var isHidden = form.classList.contains('hidden');
        document.querySelectorAll('.reject-form').forEach(function(f) { f.classList.add('hidden'); });
        document.querySelectorAll('.reject-teklif-toggle').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });
        if (isHidden) {
            form.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
            form.querySelector('textarea')?.focus();
        }
    });
});
document.querySelectorAll('.reject-teklif-cancel').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-teklif-id');
        var form = document.getElementById('reject-form-' + id);
        if (form) form.classList.add('hidden');
        var toggle = document.querySelector('.reject-teklif-toggle[data-teklif-id="' + id + '"]');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    });
});
var chatMessages = document.getElementById('chat-messages');
if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
@endpush
@endsection
