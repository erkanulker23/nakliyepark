<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();
        $selectedCategory = request('category');

        $posts = BlogPost::whereNotNull('published_at')
            ->with('category')
            ->when($selectedCategory, fn ($q) => $q->whereHas('category', fn ($cq) => $cq->where('slug', $selectedCategory)))
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'selectedCategory'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->whereNotNull('published_at')->with('category')->firstOrFail();
        $otherPosts = BlogPost::whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug', 'excerpt', 'image', 'published_at']);
        return view('blog.show', compact('post', 'otherPosts'));
    }
}
