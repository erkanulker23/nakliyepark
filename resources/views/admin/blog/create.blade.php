@extends('layouts.admin')

@section('title', 'Yeni blog yazısı')
@section('page_heading', 'Yeni blog yazısı')

@section('content')
<div class="max-w-4xl space-y-6">
    {{-- Yapay zeka ile oluştur --}}
    <div class="admin-card p-6 border-2 border-dashed border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-950/20">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <h3 class="font-semibold text-slate-800 dark:text-slate-200">Yapay zeka ile blog yazısı oluştur</h3>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Konuyu yazın, yapay zeka başlık, özet, içerik ve SEO alanlarını sizin için oluştursun. Oluşan içerik form alanlarına otomatik doldurulur, istediğiniz gibi düzenleyebilirsiniz.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="ai-topic" placeholder="Örn: Evden eve nakliyede dikkat edilmesi gerekenler" class="admin-input flex-1 py-2">
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
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-5" id="blog-form">
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
                    <input type="text" name="title" id="blog-title" value="{{ old('title') }}" required class="admin-input" placeholder="Yazı başlığı">
                    @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Slug (boş bırakırsanız başlıktan üretilir)</label>
                    <input type="text" name="slug" id="blog-slug" value="{{ old('slug') }}" class="admin-input" placeholder="ornek-yazi">
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
                <textarea name="excerpt" id="blog-excerpt" rows="3" class="admin-input" placeholder="Kısa özet">{{ old('excerpt') }}</textarea>
                @error('excerpt')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 pt-5">
                <h4 class="font-semibold text-slate-800 mb-3">SEO</h4>
                <div class="admin-form-group">
                    <label class="admin-label">Meta başlık (SEO)</label>
                    <input type="text" name="meta_title" id="blog-meta-title" value="{{ old('meta_title') }}" class="admin-input" placeholder="Arama sonuçlarında görünecek başlık">
                    @error('meta_title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Meta açıklama (SEO, max 500 karakter)</label>
                <textarea name="meta_description" id="blog-meta-description" rows="2" maxlength="500" class="admin-input" placeholder="Arama sonuçlarında görünecek açıklama">{{ old('meta_description') }}</textarea>
                @error('meta_description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">İçerik *</label>
                <textarea name="content" id="blog-content" rows="16" required class="admin-input text-sm" placeholder="HTML veya düz metin içerik">{{ old('content') }}</textarea>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                document.getElementById('blog-slug').value = slugify(d.title) || '';
                document.getElementById('blog-excerpt').value = d.excerpt || '';
                document.getElementById('blog-content').value = d.content || '';
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
