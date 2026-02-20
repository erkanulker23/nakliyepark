@extends('layouts.admin')

@section('title', 'Blog düzenle')
@section('page_heading', 'Blog düzenle')
@section('page_subtitle', $blog->title)

@section('content')
<div class="max-w-4xl space-y-6">
    @if($blog->published_at && $blog->slug)
        <div class="flex items-center justify-between gap-4 py-2 px-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/50">
            <span class="text-sm text-emerald-800 dark:text-emerald-200">Yazı yayında.</span>
            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                Yazıya git
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    @endif
    {{-- Yapay zeka ile güncelle / genişlet --}}
    <div class="admin-card p-6 border-2 border-dashed border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-950/20">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <h3 class="font-semibold text-slate-800 dark:text-slate-200">Yapay zeka ile içerik oluştur</h3>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Yeni bir konu yazın veya mevcut yazıyı genişletmek için talimat verin. Oluşan içerik form alanlarına eklenir veya değiştirilir.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="ai-topic" placeholder="Örn: Ofis taşıma checklist'i ekle" class="admin-input flex-1 py-2">
            <textarea id="ai-instructions" rows="1" placeholder="Ek talimatlar (isteğe bağlı)" class="admin-input flex-1 py-2 hidden sm:block sm:min-w-[200px]"></textarea>
            <button type="button" id="ai-generate-btn" class="admin-btn-primary py-2 whitespace-nowrap flex items-center justify-center gap-2">
                <span class="ai-btn-text">Oluştur</span>
                <span class="ai-btn-loading hidden">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
            </button>
        </div>
        <div id="ai-error" class="mt-3 text-sm text-red-600 dark:text-red-400 hidden"></div>
    </div>

    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" class="space-y-5" id="blog-form">
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
                    <input type="text" name="title" id="blog-title" value="{{ old('title', $blog->title) }}" required class="admin-input">
                    @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Slug</label>
                    <input type="text" name="slug" id="blog-slug" value="{{ old('slug', $blog->slug) }}" class="admin-input">
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
                <label class="admin-label">Makale için prompt / talimatlar</label>
                <textarea name="ai_prompt" id="blog-ai-prompt" rows="3" class="admin-input" placeholder="AI talimatları veya notlar (sadece panelde görünür)">{{ old('ai_prompt', $blog->ai_prompt) }}</textarea>
                @error('ai_prompt')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Özet</label>
                <textarea name="excerpt" id="blog-excerpt" rows="3" class="admin-input">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 mb-3">SEO</h4>
                <div class="admin-form-group">
                    <label class="admin-label">Meta başlık</label>
                    <input type="text" name="meta_title" id="blog-meta-title" value="{{ old('meta_title', $blog->meta_title) }}" class="admin-input">
                    @error('meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta açıklama</label>
                    <textarea name="meta_description" id="blog-meta-description" rows="2" maxlength="500" class="admin-input">{{ old('meta_description', $blog->meta_description) }}</textarea>
                    @error('meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İçerik *</label>
                <div id="blog-content-editor" class="admin-rich-editor-wrap bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-600 rounded-lg overflow-hidden min-h-[480px]" style="height: 480px;"></div>
                <textarea name="content" id="blog-content" required class="hidden" placeholder="Zengin metin içerik">{{ old('content', $blog->content) }}</textarea>
                @error('content')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Başlık, kalın/italik, liste, link, alıntı ve kod. İçerik frontend’de birebir aynı görünecektir.</p>
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
.admin-rich-editor-wrap .ql-toolbar.ql-snow { border: none; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
.dark .admin-rich-editor-wrap .ql-toolbar.ql-snow { background: #1e293b; border-color: #475569; }
.admin-rich-editor-wrap .ql-container.ql-snow { border: none; font-size: 15px; }
.admin-rich-editor-wrap .ql-editor { min-height: 420px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.getElementById('blog-content');
    var editorEl = document.getElementById('blog-content-editor');

    function normalizeAiContentToHtml(raw) {
        if (!raw) return '';
        var trimmed = raw.trim();
        if (!trimmed) return '';
        if (/<p>|<h[1-6]>|<ul>|<ol>|<li>/.test(trimmed) && (trimmed.match(/<[a-z][a-z0-9]*\s*\/?>/g) || []).length >= 2) {
            return trimmed;
        }
        var escaped = trimmed.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        var paras = escaped.split(/\n\s*\n/).filter(function(p) { return p.length; });
        if (paras.length === 0) paras = [escaped];
        return paras.map(function(p) {
            var withBr = p.replace(/\n/g, '<br>');
            return '<p>' + withBr + '</p>';
        }).join('');
    }

    var quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: 'İçerik yazın… Başlık, kalın, liste, link ve daha fazlası için araç çubuğunu kullanın.',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    quill.root.innerHTML = textarea.value || '';

    document.getElementById('blog-form').addEventListener('submit', function() {
        textarea.value = quill.root.innerHTML;
    });

    var btn = document.getElementById('ai-generate-btn');
    var topicInput = document.getElementById('ai-topic');
    var instructionsInput = document.getElementById('ai-instructions');
    var errorEl = document.getElementById('ai-error');
    var btnText = document.querySelector('.ai-btn-text');
    var btnLoading = document.querySelector('.ai-btn-loading');

    function slugify(text) {
        var trMap = {'ç':'c','ğ':'g','ı':'i','ö':'o','ş':'s','ü':'u','Ç':'c','Ğ':'g','İ':'i','Ö':'o','Ş':'s','Ü':'u'};
        return text.toString().toLowerCase().trim()
            .replace(/[\s\W-]+/g, '-')
            .replace(/[çğıöşüÇĞİÖŞÜ]/g, function(m){ return trMap[m] || m; })
            .replace(/-+/g, '-').replace(/^-|-$/g, '');
    }

    btn.addEventListener('click', function() {
        var topic = topicInput.value.trim();
        if (!topic) {
            errorEl.textContent = 'Lütfen bir konu girin.';
            errorEl.classList.remove('hidden');
            return;
        }
        errorEl.classList.add('hidden');
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');

        fetch('{{ route("admin.blog.generate-ai") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                topic: topic,
                additional_instructions: instructionsInput.value.trim() || null
            })
        })
        .then(function(r) { return r.json().then(function(d){ return {ok: r.ok, data: d}; }); })
        .then(function(res) {
            if (res.ok && res.data.success) {
                var d = res.data.data;
                document.getElementById('blog-title').value = d.title || '';
                document.getElementById('blog-slug').value = slugify(d.title) || document.getElementById('blog-slug').value;
                document.getElementById('blog-excerpt').value = d.excerpt || '';
                var raw = (d.content || '').trim();
                var html = normalizeAiContentToHtml(raw);
                quill.root.innerHTML = html;
                textarea.value = html;
                document.getElementById('blog-meta-title').value = d.meta_title || '';
                document.getElementById('blog-meta-description').value = d.meta_description || '';
                errorEl.classList.add('hidden');
            } else {
                errorEl.textContent = res.data.message || 'Bir hata oluştu.';
                errorEl.classList.remove('hidden');
            }
        })
        .catch(function(err) {
            errorEl.textContent = 'Bağlantı hatası. Lütfen tekrar deneyin.';
            errorEl.classList.remove('hidden');
        })
        .finally(function() {
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        });
    });
});
</script>
@endpush
@endsection
