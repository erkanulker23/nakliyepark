<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class BlocklistController extends Controller
{
    public function index()
    {
        $emails = BlockedEmail::latest()->paginate(15, ['*'], 'emails');
        $phones = BlockedPhone::latest()->paginate(15, ['*'], 'phones');
        $ips = BlockedIp::latest()->paginate(15, ['*'], 'ips');
        $blockedUsers = User::whereNotNull('blocked_at')->withCount('ihaleler')->latest('blocked_at')->get();
        $blockedCompanies = Company::whereNotNull('blocked_at')->with('user')->latest('blocked_at')->get();

        return view('admin.blocklist.index', compact('emails', 'phones', 'ips', 'blockedUsers', 'blockedCompanies'));
    }

    public function storeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reason' => 'nullable|string|max:255',
        ]);
        $email = strtolower(trim($request->email));
        if (BlockedEmail::where('email', $email)->exists()) {
            return back()->with('error', 'Bu e-posta zaten engelli.');
        }
        BlockedEmail::create(['email' => $email, 'reason' => $request->reason]);
        return back()->with('success', 'E-posta engellendi.');
    }

    public function storePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:50',
            'reason' => 'nullable|string|max:255',
        ]);
        $phone = trim($request->phone);
        if (BlockedPhone::where('phone', $phone)->exists()) {
            return back()->with('error', 'Bu telefon zaten engelli.');
        }
        BlockedPhone::create(['phone' => $phone, 'reason' => $request->reason]);
        return back()->with('success', 'Telefon engellendi.');
    }

    public function storeIp(Request $request)
    {
        $request->validate([
            'ip' => 'required|string|max:45',
            'reason' => 'nullable|string|max:255',
        ]);
        $ip = trim($request->ip);
        if (BlockedIp::where('ip', $ip)->exists()) {
            return back()->with('error', 'Bu IP zaten engelli.');
        }
        BlockedIp::create(['ip' => $ip, 'reason' => $request->reason]);
        return back()->with('success', 'IP engellendi.');
    }

    public function destroyEmail(BlockedEmail $blockedEmail)
    {
        $blockedEmail->delete();
        return back()->with('success', 'E-posta engeli kaldırıldı.');
    }

    public function destroyPhone(BlockedPhone $blockedPhone)
    {
        $blockedPhone->delete();
        return back()->with('success', 'Telefon engeli kaldırıldı.');
    }

    public function destroyIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();
        return back()->with('success', 'IP engeli kaldırıldı.');
    }

    public function blockUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi engelleyemezsiniz.');
        }
        $user->update(['blocked_at' => now()]);
        return back()->with('success', 'Kullanıcı engellendi.');
    }

    public function unblockUser(User $user)
    {
        $user->update(['blocked_at' => null]);
        return back()->with('success', 'Kullanıcı engeli kaldırıldı.');
    }

    public function blockCompany(Company $company)
    {
        $company->update(['blocked_at' => now()]);
        return back()->with('success', 'Firma engellendi.');
    }

    public function unblockCompany(Company $company)
    {
        $company->update(['blocked_at' => null]);
        return back()->with('success', 'Firma engeli kaldırıldı.');
    }
}
