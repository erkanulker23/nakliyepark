<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['ihaleler', 'reviews'])->with('company')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ], [
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'nakliyeci',
            'phone' => $request->phone,
        ]);

        $company = null;
        if ($request->filled('company_name')) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
                'approved_at' => null,
            ]);
            AdminNotifier::notify('company_created', "Admin tarafından firma oluşturuldu: {$company->name} ({$user->email})", 'Yeni firma (admin)', ['url' => route('admin.companies.edit', $company)]);
        }

        Log::channel('admin_actions')->info('Admin created nakliyeci user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'company_created' => $company !== null,
        ]);

        if ($company) {
            return redirect()->route('admin.companies.edit', $company)->with('success', 'Nakliye firması oluşturuldu. Firma bilgilerini tamamlayıp onaylayabilirsiniz.');
        }

        return redirect()->route('admin.users.edit', $user)->with('success', 'Nakliyeci kullanıcı oluşturuldu. Firma bilgisi eklemek için kullanıcıyı nakliyeci panelinden firma oluşturacak veya siz firmayı manuel ekleyebilirsiniz.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,musteri,nakliyeci',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($request->only(['name', 'email', 'role', 'phone']));
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    public function approve(User $user)
    {
        $user->sendEmailVerificationNotification();
        Log::channel('admin_actions')->info('Admin sent verification email to user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);
        return back()->with('success', 'Kullanıcıya e-posta doğrulama linki gönderildi.');
    }

    /** Nakliyeci rolündeki ancak firma oluşturmamış kullanıcıya "firma oluştur" hatırlatma maili gönderir. */
    public function sendCompanyReminder(User $user)
    {
        if (! $user->isNakliyeci()) {
            return back()->with('error', 'Bu kullanıcı nakliyeci değil.');
        }
        if ($user->company) {
            return back()->with('info', 'Bu kullanıcının zaten firması var.');
        }
        \App\Services\SafeNotificationService::sendToUser(
            $user,
            new \App\Notifications\CompanyCreateReminderNotification(),
            'admin_company_create_reminder'
        );
        Log::channel('admin_actions')->info('Admin sent company create reminder to user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);
        return back()->with('success', 'Firma oluşturma hatırlatma maili gönderildi.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz.');
        }
        $email = $user->email;
        $user->delete();
        Log::channel('admin_actions')->info('Admin user deleted', [
            'admin_id' => auth()->id(),
            'deleted_user_email' => $email,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }
}
