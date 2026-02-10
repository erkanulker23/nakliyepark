@extends('layouts.admin')

@section('title', 'Yeni blog yazısı')
@section('page_heading', 'Yeni blog yazısı')

@section('content')
<div class="max-w-4xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="admin-form-group md:col-span-2">
                    <label class="admin-label">Kategori</label>
                    <select name="category_id" class="admin-input">
                        <option value="">— Kategori seçin —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group md:col-span-2">
                    <label class="admin-label">Başlık *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="admin-input" placeholder="Yazı başlığı">
                    @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Slug (boş bırakırsanız başlıktan üretilir)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input" placeholder="ornek-yazi">
                    @error('slug')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="admin-label mb-0">Öne çıkan yazı</span>
                    </label>
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Özet (liste ve SEO için)</label>
                <textarea name="excerpt" rows="3" class="admin-input" placeholder="Kısa özet">{{ old('excerpt') }}</textarea>
                @error('excerpt')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 mb-3">SEO</h4>
                <div class="admin-form-group">
                    <label class="admin-label">Meta başlık (SEO)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="admin-input" placeholder="Arama sonuçlarında görünecek başlık">
                    @error('meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta açıklama (SEO, max 500 karakter)</label>
                <textarea name="meta_description" rows="2" maxlength="500" class="admin-input" placeholder="Arama sonuçlarında görünecek açıklama">{{ old('meta_description') }}</textarea>
                @error('meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İçerik *</label>
                <textarea name="content" rows="16" required class="admin-input text-sm" placeholder="HTML veya düz metin içerik">{{ old('content') }}</textarea>
                @error('content')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-slate-500">HTML kullanabilirsiniz: &lt;p&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;ul&gt;, &lt;h2&gt; vb. Sayfada düzgün biçimde gösterilir.</p>
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Kapak görseli</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">Dosya yükle (veya aşağıda URL girin)</label>
                        <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="admin-input py-2">
                        @error('image_file')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Veya kapak görseli URL</label>
                        <input type="text" name="image" value="{{ old('image') }}" class="admin-input" placeholder="https://...">
                        @error('image')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Yayın tarihi</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="admin-input">
                    @error('published_at')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-500">Boş bırakırsanız yayınlanmaz (taslak).</p>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.blog.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
