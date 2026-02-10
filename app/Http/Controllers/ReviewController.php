<?php

namespace App\Http\Controllers;

use App\Models\Ihale;
use App\Models\Review;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create(Ihale $ihale)
    {
        if ($ihale->user_id !== request()->user()->id) {
            abort(403);
        }
        $company = $ihale->acceptedTeklif?->company;
        if (! $company) {
            return redirect()->route('musteri.dashboard')->with('error', 'Bu ihale için onaylanmış firma yok.');
        }
        if (Review::where('user_id', request()->user()->id)->where('company_id', $company->id)->where('ihale_id', $ihale->id)->exists()) {
            return redirect()->route('musteri.dashboard')->with('info', 'Bu taşıma için zaten değerlendirme yaptınız.');
        }
        return view('reviews.create', compact('ihale', 'company'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ihale_id' => 'required|exists:ihaleler,id',
            'company_id' => 'required|exists:companies,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'video' => 'nullable|file|mimes:mp4,webm|max:51200', // 50MB, sadece mp4/webm (MIME spoofing riski azaltılır)
        ]);

        $ihale = Ihale::findOrFail($request->ihale_id);
        if ($ihale->user_id !== $request->user()->id) {
            abort(403);
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('reviews/videos', 'public');
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'company_id' => $request->company_id,
            'ihale_id' => $ihale->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'video_path' => $videoPath,
        ]);
        $company = \App\Models\Company::find($request->company_id);
        \App\Models\AuditLog::log('review_created', Review::class, (int) $review->id, null, ['company_id' => $company->id, 'ihale_id' => $ihale->id]);
        AdminNotifier::notify('review_submitted', "Yeni değerlendirme: {$request->user()->name} - {$company->name} ({$request->rating}/5)", 'Yeni değerlendirme', ['url' => route('admin.reviews.index')]);

        return redirect()->route('musteri.dashboard')->with('success', 'Değerlendirmeniz kaydedildi. Teşekkürler!');
    }
}
