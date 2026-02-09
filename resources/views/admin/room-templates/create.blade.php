@extends('layouts.admin')

@section('title', 'Yeni oda şablonu')
@section('page_heading', 'Yeni oda şablonu')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.room-templates.store') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ad *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="Örn: Salon">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Varsayılan hacim (m³) *</label>
            <input type="number" name="default_volume_m3" value="{{ old('default_volume_m3', '0') }}" step="0.01" min="0" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('default_volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Kaydet</button>
            <a href="{{ route('admin.room-templates.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500">İptal</a>
        </div>
    </form>
</div>
@endsection
