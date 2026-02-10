<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%')
                    ->orWhere('excerpt', 'like', '%' . $q . '%')
                    ->orWhere('content', 'like', '%' . $q . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->whereNotNull('published_at');
            } elseif ($request->status === 'draft') {
                $query->whereNull('published_at');
            }
        }
        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        $posts = $query->latest()->paginate(15)->withQueryString();
        $filters = $request->only(['q', 'category_id', 'status', 'featured']);
        $categories = \App\Models\BlogCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.blog.index', compact('posts', 'filters', 'categories'));
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
            'image_file' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'featured' => 'nullable|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['featured'] = $request->boolean('featured');
        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('blog', 'public');
        }
        unset($data['image_file']);
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
            'image_file' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'featured' => 'nullable|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['featured'] = $request->boolean('featured');
        if ($request->hasFile('image_file')) {
            if ($blog->image && ! Str::startsWith($blog->image, 'http') && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image_file')->store('blog', 'public');
        }
        unset($data['image_file']);
        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı güncellendi.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı silindi.');
    }
}
