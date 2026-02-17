@extends('layouts.admin')

@section('title', 'Uyuşmazlık #' . $dispute->id)
@section('page_heading', 'Uyuşmazlık detayı')

@section('content')
<div class="max-w-2xl">
    @if(session('success'))
        <div class="admin-alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="admin-card p-6 mb-6">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div><span class="text-slate-500">İhale:</span> <a href="{{ route('admin.ihaleler.show', $dispute->ihale) }}" class="text-sky-600 hover:underline">{{ $dispute->ihale->from_location_text }} → {{ $dispute->ihale->to_location_text }}</a></div>
            <div><span class="text-slate-500">Firma:</span> {{ $dispute->company->name ?? '-' }}</div>
            <div><span class="text-slate-500">Açan:</span> {{ $dispute->openedByUser->name ?? '-' }} ({{ $dispute->opened_by_type }})</div>
            <div><span class="text-slate-500">Sebep:</span> {{ \App\Models\Dispute::reasonLabels()[$dispute->reason] ?? $dispute->reason }}</div>
            <div><span class="text-slate-500">Durum:</span>
                @if($dispute->status === 'open')<span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-xs">Açık</span>
                @elseif($dispute->status === 'admin_review')<span class="px-2 py-0.5 rounded bg-sky-100 text-sky-800 text-xs">İnceleniyor</span>
                @else<span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs">Çözüldü</span> @if($dispute->resolved_at) · {{ $dispute->resolved_at->format('d.m.Y H:i') }}@endif
                @endif
            </div>
        </dl>
        @if($dispute->description)
            <div class="mt-4 pt-4 border-t border-slate-200">
                <p class="text-slate-500 text-xs font-medium uppercase mb-1">Açıklama</p>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $dispute->description }}</p>
            </div>
        @endif
        @if($dispute->admin_note)
            <div class="mt-4 pt-4 border-t border-slate-200">
                <p class="text-slate-500 text-xs font-medium uppercase mb-1">Admin notu</p>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $dispute->admin_note }}</p>
            </div>
        @endif
    </div>

    @if($dispute->status !== 'resolved')
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-100 mb-3">Durum güncelle / Çöz</h2>
        <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}" class="space-y-4">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Admin notu (isteğe bağlı)</label>
                <textarea name="admin_note" rows="4" class="admin-input" placeholder="İç görü, çözüm özeti veya taraflara iletilecek bilgi">{{ old('admin_note', $dispute->admin_note) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Durum</label>
                <select name="status" class="admin-input" required>
                    <option value="admin_review" {{ old('status', $dispute->status) === 'admin_review' ? 'selected' : '' }}>İnceleniyor</option>
                    <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>Çözüldü</option>
                </select>
            </div>
            <button type="submit" class="admin-btn-primary">Güncelle</button>
        </form>
    </div>
    @endif
</div>
@endsection
