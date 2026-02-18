<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\CompanyVehicleImage;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'companies' => Company::count(),
            'companies_pending' => Company::whereNull('approved_at')->count(),
            'companies_approved' => Company::whereNotNull('approved_at')->count(),
            'ihaleler' => Ihale::where('status', 'published')->count(),
            'recent_users_7' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_page_views' => (int) Company::sum('view_count') + (int) Ihale::where('status', 'published')->sum('view_count') + (int) BlogPost::sum('view_count'),
        ];

        $recentCompanies = Company::with('user')->latest()->take(10)->get();
        $recentIhaleler = Ihale::with('user')->latest()->take(10)->get();
        $recentUsers = User::latest()->take(10)->get();

        $mostViewedCompanies = Company::whereNotNull('approved_at')->whereNull('blocked_at')->orderByDesc('view_count')->take(10)->get(['id', 'name', 'slug', 'city', 'view_count']);
        $mostViewedIhaleler = Ihale::where('status', 'published')->orderByDesc('view_count')->take(10)->get(['id', 'from_city', 'to_city', 'slug', 'view_count']);
        $mostViewedBlogPosts = BlogPost::whereNotNull('published_at')->orderByDesc('view_count')->take(10)->get(['id', 'title', 'slug', 'view_count']);

        // Onay bekleyenler (en üstte gösterilecek)
        $pendingCompaniesCount = Company::whereNull('approved_at')->count();
        $pendingCompanies = Company::with('user')->whereNull('approved_at')->latest()->take(15)->get();
        $companiesWithPendingChangesCount = Company::whereNotNull('pending_changes_at')->count();
        $companiesWithPendingChanges = Company::whereNotNull('pending_changes_at')->latest('pending_changes_at')->take(15)->get();
        $pendingIhalelerCount = Ihale::where('status', 'pending')->count();
        $pendingIhaleler = Ihale::with('user')->where('status', 'pending')->latest()->take(15)->get();
        $tekliflerWithPendingUpdateCount = Teklif::whereNotNull('pending_amount')->count();
        $tekliflerWithPendingUpdate = Teklif::with(['ihale', 'company.user'])->whereNotNull('pending_amount')->latest()->take(15)->get();
        $galleryImagesPendingCount = CompanyVehicleImage::whereNull('approved_at')->count();
        $companiesWithUnapprovedImages = Company::whereHas('vehicleImages', fn ($q) => $q->whereNull('approved_at'))
            ->latest()->take(10)->get(['id', 'name', 'slug']);

        return view('admin.dashboard', compact(
            'stats', 'recentCompanies', 'recentIhaleler', 'recentUsers',
            'mostViewedCompanies', 'mostViewedIhaleler', 'mostViewedBlogPosts',
            'pendingCompaniesCount', 'pendingCompanies',
            'companiesWithPendingChangesCount', 'companiesWithPendingChanges',
            'pendingIhalelerCount', 'pendingIhaleler',
            'tekliflerWithPendingUpdateCount', 'tekliflerWithPendingUpdate',
            'galleryImagesPendingCount', 'companiesWithUnapprovedImages'
        ));
    }
}
