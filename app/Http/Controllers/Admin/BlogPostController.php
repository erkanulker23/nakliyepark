<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'meta_title' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'featured' => 'nullable|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['featured'] = $request->boolean('featured');
        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı eklendi.');
    }

    public function edit(BlogPost $blog)
    {
        $blog->load('category');
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'meta_title' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'featured' => 'nullable|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['featured'] = $request->boolean('featured');
        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı güncellendi.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı silindi.');
    }
}
