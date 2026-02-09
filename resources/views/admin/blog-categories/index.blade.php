@extends('layouts.admin')

@section('title', 'Blog kategorileri')
@section('page_heading', 'Blog kategorileri')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.blog-categories.create') }}" class="admin-btn-primary">Yeni kategori</a>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Sıra</th>
                <th>Ad</th>
                <th>Slug</th>
                <th>Yazı sayısı</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $c)
                <tr>
                    <td>{{ $c->sort_order }}</td>
                    <td class="font-medium">{{ $c->name }}</td>
                    <td class="text-slate-500">{{ $c->slug }}</td>
                    <td>{{ $c->posts_count ?? 0 }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.blog-categories.edit', $c) }}" class="text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.blog-categories.destroy', $c) }}" class="inline ml-2" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Kategori yok. <a href="{{ route('admin.blog-categories.create') }}" class="text-indigo-600 hover:underline">İlk kategoriyi ekleyin</a></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
