@extends('layouts.admin')

@section('title', 'İletişim mesajı')
@section('page_heading', 'İletişim mesajı')

@section('content')
<div class="max-w-2xl space-y-4">
    <div class="admin-card p-6">
        <dl class="grid sm:grid-cols-2 gap-4 mb-6">
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gönderen</dt>
                <dd class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $siteContactMessage->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">E-posta</dt>
                <dd class="mt-0.5"><a href="mailto:{{ $siteContactMessage->email }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">{{ $siteContactMessage->email }}</a></dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</dt>
                <dd class="text-slate-600 dark:text-slate-400 mt-0.5">{{ $siteContactMessage->created_at->format('d.m.Y H:i') }}</dd>
            </div>
            @if($siteContactMessage->subject)
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Konu</dt>
                    <dd class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $siteContactMessage->subject }}</dd>
                </div>
            @endif
        </dl>
        <div>
            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Mesaj</dt>
            <dd class="text-slate-700 dark:text-slate-300 whitespace-pre-line rounded-lg bg-slate-50 dark:bg-slate-800/50 p-4">{{ $siteContactMessage->message }}</dd>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.site-contact-messages.index') }}" class="admin-btn-secondary">← Listeye dön</a>
        <form method="POST" action="{{ route('admin.site-contact-messages.destroy', $siteContactMessage) }}" class="inline" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 text-sm font-medium">Sil</button>
        </form>
    </div>
</div>
@endsection
