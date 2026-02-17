<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyVehicleImage;
use App\Models\DefterApiEntry;
use App\Models\Ihale;
use App\Services\DefterApiService;
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
        ];

        $recentCompanies = Company::with('user')->latest()->take(10)->get();
        $recentIhaleler = Ihale::with('user')->latest()->take(10)->get();
        $recentUsers = User::latest()->take(10)->get();

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

        $defterApiTotal = DefterApiEntry::count();
        $defterApiNotImported = DefterApiEntry::whereNull('company_id')->count();
        $defterApiConfigured = DefterApiService::getApiUrl() !== '';

        return view('admin.dashboard', compact(
            'stats', 'recentCompanies', 'recentIhaleler', 'recentUsers',
            'pendingCompaniesCount', 'pendingCompanies',
            'companiesWithPendingChangesCount', 'companiesWithPendingChanges',
            'pendingIhalelerCount', 'pendingIhaleler',
            'tekliflerWithPendingUpdateCount', 'tekliflerWithPendingUpdate',
            'galleryImagesPendingCount', 'companiesWithUnapprovedImages',
            'defterApiTotal', 'defterApiNotImported', 'defterApiConfigured'
        ));
    }
}
