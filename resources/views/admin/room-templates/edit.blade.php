@extends('layouts.admin')

@section('title', 'Oda şablonu düzenle')
@section('page_heading', 'Oda şablonu düzenle')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.room-templates.update', $roomTemplate) }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ad *</label>
            <input type="text" name="name" value="{{ old('name', $roomTemplate->name) }}" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Varsayılan hacim (m³) *</label>
            <input type="number" name="default_volume_m3" value="{{ old('default_volume_m3', $roomTemplate->default_volume_m3) }}" step="0.01" min="0" required class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('default_volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $roomTemplate->sort_order) }}" min="0" class="w-full input-touch rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
            @error('sort_order')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium">Güncelle</button>
            <a href="{{ route('admin.room-templates.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500">İptal</a>
            <form method="POST" action="{{ route('admin.room-templates.destroy', $roomTemplate) }}" class="inline ml-auto" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">Sil</button>
            </form>
        </div>
    </form>
</div>
@endsection
