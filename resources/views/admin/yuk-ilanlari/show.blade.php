@extends('layouts.admin')

@section('title', 'Yük ilanı detay')
@section('page_heading', 'Yük ilanı detay')
@section('page_subtitle', $yuk_ilanlari->from_city . ' → ' . $yuk_ilanlari->to_city)

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.yuk-ilanlari.index') }}" class="admin-btn-secondary">← Listeye dön</a>
        <a href="{{ route('admin.yuk-ilanlari.edit', $yuk_ilanlari) }}" class="admin-btn-primary">Düzenle</a>
        <form method="POST" action="{{ route('admin.yuk-ilanlari.destroy', $yuk_ilanlari) }}" class="inline" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger">Sil</button>
        </form>
    </div>
    <div class="admin-card p-6 grid sm:grid-cols-2 gap-4">
        <div><span class="text-slate-500">Firma:</span> {{ $yuk_ilanlari->company->name ?? '-' }}</div>
        <div><span class="text-slate-500">Güzergah:</span> {{ $yuk_ilanlari->from_city }} → {{ $yuk_ilanlari->to_city }}</div>
        <div><span class="text-slate-500">Yük tipi:</span> {{ $yuk_ilanlari->load_type ?? '-' }}</div>
        <div><span class="text-slate-500">Tarih:</span> {{ $yuk_ilanlari->load_date?->format('d.m.Y') ?? '-' }}</div>
        <div><span class="text-slate-500">Hacim:</span> {{ $yuk_ilanlari->volume_m3 ?? '-' }} m³</div>
        <div><span class="text-slate-500">Durum:</span> {{ $yuk_ilanlari->status }}</div>
        @if($yuk_ilanlari->description)
            <div class="sm:col-span-2"><span class="text-slate-500">Açıklama:</span> {{ $yuk_ilanlari->description }}</div>
        @endif
    </div>
</div>
@endsection
