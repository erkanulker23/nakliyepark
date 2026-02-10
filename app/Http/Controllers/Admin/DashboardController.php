<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Ihale;
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

        return view('admin.dashboard', compact('stats', 'recentCompanies', 'recentIhaleler', 'recentUsers'));
    }
}
