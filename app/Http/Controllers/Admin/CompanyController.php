<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Notifications\CompanyApprovedNotification;
use App\Services\AdminNotifier;
use App\Services\SafeNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with('user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%' . $q . '%')
                            ->orWhere('email', 'like', '%' . $q . '%');
                    });
            });
        }
        
        // Onay filtresi: sadece kullanıcı seçtiğinde uygula (varsayılan: tümü)
        if ($request->approved === '1') {
            $query->whereNotNull('approved_at');
        } elseif ($request->approved === '0') {
            $query->whereNull('approved_at');
        }
        if ($request->filled('blocked')) {
            if ($request->blocked === '1') {
                $query->whereNotNull('blocked_at');
            } else {
                $query->whereNull('blocked_at');
            }
        }
        if ($request->filled('package')) {
            $query->where('package', $request->package);
        }

        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        if (in_array($sort, ['name', 'city', 'created_at', 'approved_at'])) {
            $query->orderBy($sort, $dir);
        } else {
            $query->latest();
        }

        $companies = $query->paginate(15)->withQueryString();
        $filters = $request->only(['q', 'approved', 'blocked', 'package', 'sort', 'dir']);
        $paketler = config('nakliyepark.nakliyeci_paketler', []);

        return view('admin.companies.index', compact('companies', 'filters', 'paketler'));
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        $company->load('user', 'vehicleImages');
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        return view('admin.companies.edit', compact('company', 'paketler'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'tax_office' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'description' => 'nullable|string',
            'package' => 'nullable|string|in:baslangic,profesyonel,kurumsal',
            'seo_meta_title' => 'nullable|string|max:255',
            'seo_meta_description' => 'nullable|string|max:500',
            'seo_meta_keywords' => 'nullable|string|max:500',
            'services' => 'nullable|array',
            'services.*' => 'string|in:evden_eve_nakliyat,sehirlerarasi_nakliyat,ofis_tasima,esya_depolama,uluslararasi_nakliyat',
        ]);
        $data = $request->only([
            'name', 'tax_number', 'tax_office', 'address', 'city', 'district',
            'phone', 'phone_2', 'whatsapp', 'email', 'description', 'package',
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
        ]);
        $data['services'] = $request->input('services', []);
        $wasApproved = $company->approved_at !== null;
        $company->load('user');
        $company->update($data);

        // Firma e-postası ve giriş (User) e-postasını senkronize et; panel e-posta doğrulama sayfasına düşmesin.
        if ($company->user) {
            $newEmail = trim((string) $request->input('email', ''));
            $user = $company->user;
            if ($newEmail !== '' && $user->email !== $newEmail) {
                $user->update([
                    'email' => $newEmail,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
                Log::channel('admin_actions')->info('Admin synced company email to user', [
                    'admin_id' => auth()->id(),
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'new_email' => $newEmail,
                ]);
            } elseif ($newEmail !== '' && ! $user->email_verified_at) {
                // E-posta zaten aynı ama kullanıcı hâlâ doğrulanmamış (örn. admin mail değiştirdikten sonra link eski mailde kaldı)
                $user->update(['email_verified_at' => now()]);
                Log::channel('admin_actions')->info('Admin marked user email as verified', [
                    'admin_id' => auth()->id(),
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        if ($request->has('approved')) {
            $approved = $request->boolean('approved');
            $company->update([
                'approved_at' => $approved ? now() : null,
                'email_verified_at' => $approved ? now() : null,
                'phone_verified_at' => $approved ? now() : null,
                'official_company_verified_at' => $approved ? now() : null,
            ]);
            
            // Eğer daha önce onaylanmamışsa ve şimdi onaylandıysa, kullanıcıya e-posta gönder
            if (!$wasApproved && $approved && $company->user) {
                SafeNotificationService::sendToUser(
                    $company->user,
                    new CompanyApprovedNotification($company),
                    'Company approved via update'
                );
            }
        }
        return redirect()->route('admin.companies.edit', $company)->with('success', 'Firma güncellendi.');
    }

    public function destroy(Request $request, Company $company)
    {
        $request->validate(['action_reason' => 'nullable|string|max:1000']);
        $before = $company->only(['id', 'name', 'user_id', 'approved_at', 'created_at']);
        $company->delete();
        AuditLog::adminAction('admin_company_deleted', Company::class, (int) $company->id, $before, ['deleted_at' => now()->toIso8601String()], $request->input('action_reason'));
        Log::channel('admin_actions')->info('Admin company deleted', ['admin_id' => auth()->id(), 'company_id' => $company->id, 'company_name' => $company->name]);
        return redirect()->route('admin.companies.index')->with('success', 'Firma silindi. Geri almak için destek ile iletişime geçin.');
    }

    public function approve(Company $company)
    {
        $wasApproved = $company->approved_at !== null;
        $company->load('user');
        $now = now();
        $company->update([
            'approved_at' => $now,
            'email_verified_at' => $now,
            'phone_verified_at' => $now,
            'official_company_verified_at' => $now,
        ]);
        Log::channel('admin_actions')->info('Admin company approved', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
        AdminNotifier::notify('company_approved', "Firma onaylandı: {$company->name}", 'Firma onayı', ['url' => route('admin.companies.edit', $company)]);
        
        // Eğer daha önce onaylanmamışsa, kullanıcıya e-posta gönder
        if (!$wasApproved && $company->user) {
            SafeNotificationService::sendToUser(
                $company->user,
                new CompanyApprovedNotification($company),
                'Company approved notification'
            );
        }
        
        return back()->with('success', 'Firma onaylandı.');
    }

    public function reject(Company $company)
    {
        $company->update([
            'approved_at' => null,
            'email_verified_at' => null,
            'phone_verified_at' => null,
            'official_company_verified_at' => null,
        ]);
        Log::channel('admin_actions')->info('Admin company rejected', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
        return back()->with('success', 'Firma onayı kaldırıldı.');
    }

    /** Nakliyecinin gönderdiği bekleyen değişiklikleri onayla; firma yayında kalır, değişiklikler yayına alınır. */
    public function approvePendingChanges(Company $company)
    {
        $this->authorize('update', $company);
        if (! $company->hasPendingChanges()) {
            return back()->with('error', 'Bekleyen değişiklik yok.');
        }
        $pending = $company->pending_changes;
        $data = [
            'name' => $pending['name'] ?? $company->name,
            'tax_number' => $pending['tax_number'] ?? $company->tax_number,
            'tax_office' => $pending['tax_office'] ?? $company->tax_office,
            'address' => $pending['address'] ?? $company->address,
            'city' => $pending['city'] ?? $company->city,
            'district' => $pending['district'] ?? $company->district,
            'phone' => $pending['phone'] ?? $company->phone,
            'phone_2' => $pending['phone_2'] ?? $company->phone_2,
            'whatsapp' => $pending['whatsapp'] ?? $company->whatsapp,
            'email' => $pending['email'] ?? $company->email,
            'description' => $pending['description'] ?? $company->description,
            'services' => $pending['services'] ?? $company->services ?? [],
            'seo_meta_title' => $pending['seo_meta_title'] ?? $company->seo_meta_title,
            'seo_meta_description' => $pending['seo_meta_description'] ?? $company->seo_meta_description,
            'seo_meta_keywords' => $pending['seo_meta_keywords'] ?? $company->seo_meta_keywords,
        ];
        if (! empty($pending['remove_logo'])) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = null;
            $data['logo_approved_at'] = null;
        } elseif (! empty($pending['logo'])) {
            if ($company->logo && $company->logo !== $pending['logo']) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $pending['logo'];
            $data['logo_approved_at'] = now();
        }
        $company->update($data);
        if (isset($pending['name'])) {
            $company->slug = $company->generateSlug();
            $company->saveQuietly();
        }
        $company->update(['pending_changes' => null, 'pending_changes_at' => null]);
        Log::channel('admin_actions')->info('Admin approved company pending changes', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
        return back()->with('success', 'Firmanın gönderdiği değişiklikler onaylandı ve yayına alındı.');
    }

    /** Listeden hızlı paket atama (AJAX veya form submit). */
    public function updatePackage(Request $request, Company $company)
    {
        $request->validate([
            'package' => 'nullable|string|in:baslangic,profesyonel,kurumsal',
        ]);
        $oldPackage = $company->package;
        $company->update(['package' => $request->package ?: null]);
        \App\Models\AuditLog::log('company_package_changed', Company::class, (int) $company->id, ['package' => $oldPackage], ['package' => $company->package]);
        return back()->with('success', 'Firma paketi güncellendi.');
    }

    /** Firma logosunu onayla (nakliyeci yükledikten sonra). */
    public function approveLogo(Company $company)
    {
        $this->authorize('update', $company);
        if (! $company->logo) {
            return back()->with('error', 'Firmada yüklü logo yok.');
        }
        $company->update(['logo_approved_at' => now()]);
        Log::channel('admin_actions')->info('Admin company logo approved', ['admin_id' => auth()->id(), 'company_id' => $company->id]);
        return back()->with('success', 'Logo onaylandı. Firma sayfasında görünecek.');
    }

    /** Galeri fotoğrafını onayla. */
    public function approveGalleryImage(Request $request, Company $company, int $id)
    {
        $this->authorize('update', $company);
        $image = $company->vehicleImages()->findOrFail($id);
        $image->update(['approved_at' => now()]);
        Log::channel('admin_actions')->info('Admin company gallery image approved', ['admin_id' => auth()->id(), 'company_id' => $company->id, 'image_id' => $id]);
        return back()->with('success', 'Galeri fotoğrafı onaylandı.');
    }

    /** Galerideki tüm onay bekleyen fotoğrafları onayla. */
    public function approveAllGalleryImages(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $count = $company->vehicleImages()->whereNull('approved_at')->update(['approved_at' => now()]);
        Log::channel('admin_actions')->info('Admin company gallery approve all', ['admin_id' => auth()->id(), 'company_id' => $company->id, 'count' => $count]);
        return back()->with('success', $count > 0 ? "{$count} galeri fotoğrafı onaylandı." : 'Onay bekleyen fotoğraf yok.');
    }

    /** Galeriden fotoğrafı kaldır (sil). */
    public function destroyGalleryImage(Request $request, Company $company, int $id)
    {
        $this->authorize('update', $company);
        $image = $company->vehicleImages()->findOrFail($id);
        $path = $image->path;
        $image->delete();
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        Log::channel('admin_actions')->info('Admin company gallery image removed', ['admin_id' => auth()->id(), 'company_id' => $company->id, 'image_id' => $id]);
        return back()->with('success', 'Galeri fotoğrafı kaldırıldı.');
    }
}
