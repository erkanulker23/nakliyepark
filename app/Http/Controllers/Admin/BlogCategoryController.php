<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        BlogCategory::create($data);
        return redirect()->route('admin.blog-categories.index')->with('success', 'Kategori eklendi.');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('admin.blog-categories.edit', compact('blog_category'));
    }

    public function update(Request $request, BlogCategory $blog_category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $blog_category->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $blog_category->update($data);
        return redirect()->route('admin.blog-categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(BlogCategory $blog_category)
    {
        $blog_category->posts()->update(['category_id' => null]);
        $blog_category->delete();
        return redirect()->route('admin.blog-categories.index')->with('success', 'Kategori silindi.');
    }
}
