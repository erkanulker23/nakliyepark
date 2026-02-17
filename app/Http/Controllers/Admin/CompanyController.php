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
        
        // Varsayılan olarak onay bekleyen firmaları göster (eğer hiç filtre yoksa)
        if ($request->has('approved')) {
            // Kullanıcı açıkça bir seçim yapmış
            if ($request->approved === '1') {
                $query->whereNotNull('approved_at');
            } elseif ($request->approved === '0') {
                $query->whereNull('approved_at');
            }
            // Eğer approved boş string ise (Tümü seçilmiş), filtreleme yapma
        } else {
            // Hiç filtre yoksa ve query string boşsa, varsayılan olarak onay bekleyen firmaları göster
            if (!$request->hasAny(['q', 'blocked', 'package', 'sort', 'dir'])) {
                $query->whereNull('approved_at');
            }
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
        
        // Eğer hiç filtre yoksa ve varsayılan olarak onay bekleyenleri gösteriyorsak, filters'a ekle
        if (!$request->hasAny(['q', 'approved', 'blocked', 'package', 'sort', 'dir'])) {
            $filters['approved'] = '0';
        }
        
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
}
