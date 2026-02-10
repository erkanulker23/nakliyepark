@extends('layouts.admin')

@section('title', 'Blog')
@section('page_heading', 'Blog yazıları')

@section('content')
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.blog.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Başlık, slug veya içerik ara..." class="admin-input py-2 w-52 text-sm">
        <select name="category_id" class="admin-input py-2 w-40 text-sm">
            <option value="">Tüm kategoriler</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (isset($filters['category_id']) && (string)$filters['category_id'] === (string)$cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="admin-input py-2 w-36 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Yayında</option>
            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Taslak</option>
        </select>
        <select name="featured" class="admin-input py-2 w-32 text-sm">
            <option value="">Öne çıkan</option>
            <option value="1" {{ (isset($filters['featured']) && $filters['featured'] === '1') ? 'selected' : '' }}>Evet</option>
            <option value="0" {{ (isset($filters['featured']) && $filters['featured'] === '0') ? 'selected' : '' }}>Hayır</option>
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.blog.index') }}" class="text-slate-500 hover:underline text-sm py-2">Temizle</a>
        @endif
    </form>
    <a href="{{ route('admin.blog.create') }}" class="admin-btn-primary">Yeni yazı</a>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Slug</th>
                <th>Yayın</th>
                <th>Öne çıkan</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $p)
                <tr>
                    <td class="font-medium text-slate-800">{{ $p->title }}</td>
                    <td class="text-slate-600">{{ $p->category?->name ?? '—' }}</td>
                    <td class="text-slate-500 text-sm">{{ $p->slug }}</td>
                    <td class="text-slate-600 text-sm">{{ $p->published_at?->format('d.m.Y') ?? '—' }}</td>
                    <td>@if($p->featured)<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Öne çıkan</span>@else — @endif</td>
                    <td class="text-right">
                        @if($p->status === 'published' && $p->slug)
                            <a href="{{ route('blog.show', $p->slug) }}" target="_blank" rel="noopener" class="text-slate-600 dark:text-slate-400 hover:underline text-sm font-medium">Blog sayfasına git</a>
                            <span class="text-slate-300 dark:text-slate-600 mx-1">|</span>
                        @endif
                        <a href="{{ route('admin.blog.edit', $p) }}" class="text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $p) }}" class="inline ml-2" onsubmit="return confirm('Bu yazıyı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Blog yazısı yok. <a href="{{ route('admin.blog.create') }}" class="text-indigo-600 hover:underline">İlk yazıyı ekleyin</a></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($posts->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
