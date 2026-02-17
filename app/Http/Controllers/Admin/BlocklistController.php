<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::channel('admin_actions')->info('Admin blocklist email added', ['admin_id' => auth()->id(), 'email' => $email]);
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
        Log::channel('admin_actions')->info('Admin blocklist phone added', ['admin_id' => auth()->id(), 'phone' => $phone]);
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
        Log::channel('admin_actions')->info('Admin blocklist IP added', ['admin_id' => auth()->id(), 'ip' => $ip]);
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
        Log::channel('admin_actions')->info('Admin user blocked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);
        return back()->with('success', 'Kullanıcı engellendi.');
    }

    public function unblockUser(User $user)
    {
        $user->update(['blocked_at' => null]);
        Log::channel('admin_actions')->info('Admin user unblocked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);
        return back()->with('success', 'Kullanıcı engeli kaldırıldı.');
    }

    public function blockCompany(Request $request, Company $company)
    {
        $request->validate([
            'blocked_reason' => 'nullable|string|max:500',
            'blocked_reason_type' => 'nullable|string|in:borc,sozlesme_ihlali,diger',
        ]);
        $reasonType = $request->input('blocked_reason_type');
        $reasonText = trim((string) $request->input('blocked_reason', ''));
        if ($reasonType) {
            $reason = Company::blockedReasonLabel($reasonType);
            if ($reasonText !== '') {
                $reason .= ': ' . $reasonText;
            }
        } else {
            $reason = $reasonText;
        }
        $reason = $reason !== '' ? $reason : null;
        $company->update([
            'blocked_at' => now(),
            'blocked_reason' => $reason ?: null,
        ]);
        Log::channel('admin_actions')->info('Admin company blocked (üyelik askıya alındı)', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
            'blocked_reason' => $reason,
        ]);
        return back()->with('success', 'Nakliyeci üyeliği askıya alındı.');
    }

    public function unblockCompany(Company $company)
    {
        $company->update(['blocked_at' => null, 'blocked_reason' => null]);
        Log::channel('admin_actions')->info('Admin company unblocked (askı kaldırıldı)', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
        return back()->with('success', 'Üyelik askısı kaldırıldı.');
    }
}
