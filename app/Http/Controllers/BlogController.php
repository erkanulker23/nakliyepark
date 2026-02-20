<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Setting;

class BlogController extends Controller
{
    public function index()
    {
        if (Setting::get('show_blog_page', '1') !== '1') {
            abort(404);
        }
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();
        $selectedCategory = request('category');
        $searchQuery = request('q', '');

        $posts = BlogPost::whereNotNull('published_at')
            ->with('category')
            ->when($selectedCategory, fn ($q) => $q->whereHas('category', fn ($cq) => $cq->where('slug', $selectedCategory)))
            ->when($searchQuery !== '', function ($q) use ($searchQuery) {
                $q->where(function ($qry) use ($searchQuery) {
                    $qry->where('title', 'like', '%' . $searchQuery . '%')
                        ->orWhere('excerpt', 'like', '%' . $searchQuery . '%')
                        ->orWhere('content', 'like', '%' . $searchQuery . '%');
                });
            })
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'selectedCategory', 'searchQuery'));
    }

    public function show(string $slug)
    {
        if (Setting::get('show_blog_page', '1') !== '1') {
            abort(404);
        }
        $post = BlogPost::where('slug', $slug)->whereNotNull('published_at')->with('category')->firstOrFail();
        $post->increment('view_count');
        $otherPosts = BlogPost::whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug', 'excerpt', 'image', 'published_at']);
        return view('blog.show', compact('post', 'otherPosts'));
    }
}
