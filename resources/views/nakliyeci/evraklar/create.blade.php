@extends('layouts.nakliyeci')

@section('title', 'Evrak yükle')
@section('page_heading', 'Şirket evrakı yükle')
@section('page_subtitle', 'K1, marka tescil, ODE, psikoteknik, faaliyet, vergi levhası, ticaret odası')

@section('content')
<div class="max-w-xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('nakliyeci.evraklar.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Evrak türü *</label>
                <select name="type" required class="admin-input">
                    @foreach(\App\Http\Controllers\Nakliyeci\EvraklarController::TYPES as $key => $label)
                        <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Başlık (opsiyonel)</label>
                <input type="text" name="title" value="{{ old('title') }}" class="admin-input" placeholder="Özel bir ad verin">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Dosya *</label>
                <input type="file" name="file" accept=".pdf,image/jpeg,image/png,image/jpg" required class="admin-input">
                <p class="text-xs text-slate-500 mt-1">PDF veya resim. En fazla 10 MB.</p>
                @error('file')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Son geçerlilik tarihi (opsiyonel)</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="admin-input">
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Yükle</button>
                <a href="{{ route('nakliyeci.evraklar.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
