@extends('layouts.admin')

@section('title', 'Teklif düzenle')
@section('page_heading', 'Teklif düzenle')

@section('content')
<div class="max-w-2xl">
    @if($teklif->hasPendingUpdate())
    <div class="admin-card p-4 mb-6 border-amber-200 dark:border-amber-800 bg-amber-50/50 dark:bg-amber-900/10">
        <p class="font-medium text-amber-800 dark:text-amber-200 mb-2">Bekleyen güncelleme talebi</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Nakliyeci tutar/mesaj değişikliği talep etti. Onaylarsanız mevcut teklif güncellenir.</p>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm mb-4">
            <div><span class="text-slate-500">Mevcut tutar:</span> {{ number_format($teklif->amount, 0, ',', '.') }} ₺</div>
            <div><span class="text-slate-500">Talep edilen:</span> <strong>{{ number_format($teklif->pending_amount, 0, ',', '.') }} ₺</strong></div>
        </dl>
        <div class="flex flex-wrap gap-2 items-end">
            <form method="POST" action="{{ route('admin.teklifler.approve-pending', $teklif) }}" class="inline" onsubmit="return confirm('Bekleyen güncellemeyi onaylıyor musunuz?');">
                @csrf
                <button type="submit" class="admin-btn-primary">Onayla</button>
            </form>
            <form method="POST" action="{{ route('admin.teklifler.reject-pending', $teklif) }}" class="inline flex flex-wrap gap-2 items-end" onsubmit="return confirm('Güncelleme talebini reddedeceksiniz. Gerekçe nakliyeciye gösterilir.');">
                @csrf
                <input type="text" name="reject_reason" class="admin-input w-64" placeholder="Red gerekçesi (nakliyeciye gösterilir)" value="{{ old('reject_reason') }}" maxlength="1000">
                <button type="submit" class="admin-btn-secondary">Reddet</button>
            </form>
        </div>
    </div>
    @endif

    @if($teklif->status === 'accepted')
    <div class="admin-card p-6 mb-6 border-amber-200 dark:border-amber-800 bg-amber-50/30 dark:bg-amber-900/10">
        <p class="font-medium text-amber-800 dark:text-amber-200 mb-2">Kabul edilmiş teklif (salt okunur)</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Ticari tutarlılık için kabul edilmiş teklifler düzenlenemez. Bu işlem firma ve müşteriyi doğrudan etkiler.</p>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
            <div><span class="text-slate-500">Tutar:</span> <strong>{{ number_format($teklif->amount, 0, ',', '.') }} ₺</strong></div>
            <div><span class="text-slate-500">Durum:</span> Kabul edildi</div>
            @if($teklif->message)<div class="sm:col-span-2"><span class="text-slate-500">Mesaj:</span> {{ $teklif->message }}</div>@endif
        </dl>
        <form method="POST" action="{{ route('admin.teklifler.destroy', $teklif) }}" class="inline mt-4" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <input type="text" name="action_reason" class="admin-input py-1.5 w-56 text-sm mr-2" placeholder="Silme nedeni (isteğe bağlı)" maxlength="1000">
            <button type="submit" class="admin-btn-danger text-sm">Sil</button>
        </form>
    </div>
    @else
    <div class="admin-card p-6">
        <p class="text-sm text-slate-500 mb-2">İhale: {{ $teklif->ihale->from_location_text }} → {{ $teklif->ihale->to_location_text }} · Firma: {{ $teklif->company->name }}</p>
        <p class="text-xs text-amber-600 dark:text-amber-400 mb-4">Bu işlem firma ve müşteriyi etkiler. Tutar veya durum değişikliği yaparken dikkatli olun.</p>
        <form method="POST" action="{{ route('admin.teklifler.update', $teklif) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Tutar (₺) *</label>
                <input type="number" name="amount" value="{{ old('amount', $teklif->amount) }}" step="0.01" min="0" required class="admin-input">
                @error('amount')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Mesaj</label>
                <textarea name="message" rows="3" class="admin-input">{{ old('message', $teklif->message) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum *</label>
                <select name="status" required class="admin-input">
                    <option value="pending" {{ old('status', $teklif->status) === 'pending' ? 'selected' : '' }}>Beklemede</option>
                    <option value="accepted" {{ old('status', $teklif->status) === 'accepted' ? 'selected' : '' }}>Kabul</option>
                    <option value="rejected" {{ old('status', $teklif->status) === 'rejected' ? 'selected' : '' }}>Red</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.teklifler.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
        <form method="POST" action="{{ route('admin.teklifler.destroy', $teklif) }}" class="inline mt-3" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <input type="text" name="action_reason" class="admin-input py-1.5 w-48 text-sm mr-1" placeholder="Silme nedeni (isteğe bağlı)" maxlength="1000">
            <button type="submit" class="admin-btn-danger">Sil</button>
        </form>
    </div>
    @endif
</div>
@endsection
