@extends('layouts.musteri')

@section('title', 'İhale detay - ' . $ihale->from_city . ' → ' . $ihale->to_city)
@section('page_heading', 'İhale detayı')
@section('page_subtitle', $ihale->from_city . ' → ' . $ihale->to_city)

@section('content')
<div class="max-w-3xl">
    <div class="admin-card p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $ihale->from_city }} → {{ $ihale->to_city }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $ihale->volume_m3 }} m³
                    @if($ihale->move_date || $ihale->move_date_end)
                        · @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
                            {{ $ihale->move_date?->format('d.m.Y') }} – {{ $ihale->move_date_end?->format('d.m.Y') }}
                        @else
                            {{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') }}
                        @endif
                    @else
                        Fiyat bakıyorum
                    @endif
                </p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full
                @if($ihale->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300
                @elseif($ihale->status === 'published') bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300
                @elseif($ihale->status === 'closed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                @else bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300
                @endif">
                {{ $ihale->status === 'pending' ? 'Onay bekliyor' : ($ihale->status === 'published' ? 'Yayında' : ($ihale->status === 'closed' ? 'Kapalı' : 'Taslak')) }}
            </span>
        </div>
        @if($ihale->description)
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-4 whitespace-pre-line">{{ $ihale->description }}</p>
        @endif
    </div>

    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-100 mb-4">Gelen teklifler ({{ $ihale->teklifler->count() }})</h2>
        @if($acceptedTeklif)
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Kabul ettiğiniz teklif</p>
                <p class="text-xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">{{ number_format($acceptedTeklif->amount, 0, ',', '.') }} ₺</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $acceptedTeklif->company->name ?? '-' }}</p>
                @if($acceptedTeklif->company)
                    @if($acceptedTeklif->company->phone)
                        <p class="text-sm mt-2">Tel: <a href="tel:{{ $acceptedTeklif->company->phone }}" class="text-sky-600 dark:text-sky-400">{{ $acceptedTeklif->company->phone }}</a></p>
                    @endif
                    @if($acceptedTeklif->company->whatsapp)
                        <p class="text-sm">WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $acceptedTeklif->company->whatsapp) }}" target="_blank" rel="noopener" class="text-sky-600 dark:text-sky-400">{{ $acceptedTeklif->company->whatsapp }}</a></p>
                    @endif
                @endif
                @if($acceptedTeklif->canUndoAccept())
                    <form method="POST" action="{{ route('musteri.ihaleler.undo-accept', [$ihale, $acceptedTeklif]) }}" class="mb-4 inline-block" onsubmit="return confirm('Teklif kabulünü geri almak istediğinize emin misiniz? İhale tekrar yayına döner.');">
                        @csrf
                        <button type="submit" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Kabulü geri al ({{ \App\Models\Teklif::ACCEPT_UNDO_MINUTES }} dk içinde)</button>
                    </form>
                @endif
                <div class="mt-4 pt-4 border-t border-emerald-200/80 dark:border-emerald-800/80">
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nakliyeciye soru sor</p>
                    <form action="{{ route('musteri.ihaleler.contact-message', [$ihale, $acceptedTeklif]) }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="message" rows="3" class="admin-input w-full" placeholder="Taşınma tarihi, özel istekleriniz veya sorularınızı yazın; nakliyeci e-posta ile bilgilendirilir." required maxlength="2000"></textarea>
                        <button type="submit" class="admin-btn-primary text-sm py-2 px-4 rounded-lg">Gönder</button>
                    </form>
                </div>
                <a href="{{ route('review.create', $ihale) }}" class="inline-block mt-3 text-sm text-sky-600 dark:text-sky-400 font-medium">Değerlendirme yap →</a>
                <div class="mt-4 pt-4 border-t border-emerald-200/80 dark:border-emerald-800/80">
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sorun mu yaşadınız?</p>
                    <form action="{{ route('musteri.ihaleler.dispute.store', $ihale) }}" method="POST" class="space-y-2">
                        @csrf
                        <select name="reason" class="admin-input w-full max-w-xs text-sm" required>
                            <option value="">Sebep seçin</option>
                            @foreach(\App\Models\Dispute::reasonLabels() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="description" rows="2" class="admin-input w-full text-sm" placeholder="Kısa açıklama (isteğe bağlı)" maxlength="2000"></textarea>
                        <button type="submit" class="admin-btn-secondary text-sm py-2 px-4 rounded-lg">Şikâyet / uyuşmazlık aç</button>
                    </form>
                </div>
            </div>
        @endif
        <ul class="space-y-4">
            @forelse($ihale->teklifler as $t)
                <li class="flex flex-wrap items-center justify-between gap-4 py-4 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <div>
                        <p class="font-medium text-slate-800 dark:text-slate-100">{{ $t->company->name ?? '-' }}</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                        @if($t->message)
                            <p class="text-sm text-slate-500 mt-1">{{ $t->message }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">{{ $t->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        @if($t->status === 'accepted')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Kabul edildi</span>
                        @elseif($t->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Reddedildi</span>
                        @elseif(!$acceptedTeklif && $ihale->status === 'published')
                            <form method="POST" action="{{ route('musteri.ihaleler.accept-teklif', [$ihale, $t]) }}" class="inline" onsubmit="return confirm('Bu teklifi kabul etmek istediğinize emin misiniz? Diğer teklifler reddedilir.');">
                                @csrf
                                <button type="submit" class="admin-btn-primary text-sm py-2 px-4 rounded-lg">Teklifi kabul et</button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600">Beklemede</span>
                        @endif
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-slate-500">Henüz teklif gelmedi.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
