<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'company', 'ihale']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('comment', 'like', '%' . $q . '%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%');
                    })
                    ->orWhereHas('company', function ($c) use ($q) {
                        $c->where('name', 'like', '%' . $q . '%');
                    });
            });
        }
        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $filters = $request->only(['q', 'rating']);

        return view('admin.reviews.index', compact('reviews', 'filters'));
    }

    public function edit(Review $review)
    {
        $review->load(['user', 'company', 'ihale']);
        $companies = \App\Models\Company::orderBy('name')->get(['id', 'name', 'city']);
        return view('admin.reviews.edit', compact('review', 'companies'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'video_path' => 'nullable|string|max:500',
        ]);
        $review->update($validated);
        return redirect()->route('admin.reviews.index')->with('success', 'Değerlendirme güncellendi.');
    }

    public function destroy(Request $request, Review $review)
    {
        $request->validate(['action_reason' => 'nullable|string|max:1000']);
        $before = $review->only(['id', 'user_id', 'company_id', 'ihale_id', 'rating', 'created_at']);
        $review->delete();
        AuditLog::adminAction('admin_review_deleted', Review::class, (int) $review->id, $before, ['deleted_at' => now()->toIso8601String()], $request->input('action_reason'));
        return redirect()->route('admin.reviews.index')->with('success', 'Değerlendirme silindi.');
    }
}
