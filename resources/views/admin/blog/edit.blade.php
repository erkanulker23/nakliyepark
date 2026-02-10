@extends('layouts.admin')

@section('title', 'Blog düzenle')
@section('page_heading', 'Blog düzenle')
@section('page_subtitle', $blog->title)

@section('content')
<div class="max-w-4xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="admin-form-group md:col-span-2">
                    <label class="admin-label">Kategori</label>
                    <select name="category_id" class="admin-input">
                        <option value="">— Kategori seçin —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group md:col-span-2">
                    <label class="admin-label">Başlık *</label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}" required class="admin-input">
                    @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $blog->slug) }}" class="admin-input">
                    @error('slug')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $blog->featured) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="admin-label mb-0">Öne çıkan yazı</span>
                    </label>
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Özet</label>
                <textarea name="excerpt" rows="3" class="admin-input">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 mb-3">SEO</h4>
                <div class="admin-form-group">
                    <label class="admin-label">Meta başlık</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="admin-input">
                    @error('meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta açıklama</label>
                    <textarea name="meta_description" rows="2" maxlength="500" class="admin-input">{{ old('meta_description', $blog->meta_description) }}</textarea>
                    @error('meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İçerik *</label>
                <textarea name="content" rows="16" required class="admin-input text-sm font-mono" placeholder="Metin veya HTML (p, strong, ul, h2 vb.)">{{ old('content', $blog->content) }}</textarea>
                @error('content')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Bu alan sayfada aynen gösterilir. HTML etiketleri kullanabilirsiniz: &lt;p&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;ul&gt;, &lt;h2&gt; vb.</p>
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Kapak görseli</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="admin-form-group">
                        <label class="admin-label">Dosya yükle (veya aşağıda URL girin)</label>
                        <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="admin-input py-2">
                        @error('image_file')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        @if($blog->image)
                            @php
                                $imgSrc = Str::startsWith($blog->image, 'http') ? $blog->image : asset('storage/'.$blog->image);
                            @endphp
                            <p class="mt-2 text-xs text-slate-500">Mevcut görsel:</p>
                            <img src="{{ $imgSrc }}" alt="Kapak" class="mt-1 h-24 rounded-lg border border-slate-200 object-cover">
                        @endif
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Veya kapak görseli URL</label>
                        <input type="text" name="image" value="{{ old('image', $blog->image) }}" class="admin-input" placeholder="https://...">
                        @error('image')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Yayın tarihi</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
                    @error('published_at')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Güncelle</button>
                <a href="{{ route('admin.blog.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
        <form method="POST" action="{{ route('admin.blog.destroy', $blog) }}" class="inline mt-3" onsubmit="return confirm('Bu yazıyı silmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger">Sil</button>
        </form>
    </div>
</div>
@endsection
