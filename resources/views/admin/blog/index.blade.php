@extends('layouts.admin')

@section('title', 'Blog')
@section('page_heading', 'Blog yazıları')

@section('content')
<div class="flex justify-end mb-6">
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
