<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Notifications\CompanyApprovedNotification;
use App\Notifications\CompanyCredentialsSetPasswordNotification;
use App\Services\AdminNotifier;
use App\Services\GooglePlacesService;
use App\Services\SafeNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
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
            'google_maps_url' => 'nullable|string|max:500',
            'google_reviews_url' => 'nullable|string|max:500',
            'google_rating' => 'nullable|numeric|min:0|max:5',
            'google_review_count' => 'nullable|integer|min:0',
            'yandex_reviews_url' => 'nullable|string|max:500',
            'yandex_rating' => 'nullable|numeric|min:0|max:5',
            'yandex_review_count' => 'nullable|integer|min:0',
        ]);
        $data = $request->only([
            'name', 'tax_number', 'tax_office', 'address', 'city', 'district',
            'phone', 'phone_2', 'whatsapp', 'email', 'description', 'package',
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
            'google_maps_url', 'google_reviews_url', 'google_rating', 'google_review_count',
            'yandex_reviews_url', 'yandex_rating', 'yandex_review_count',
        ]);
        $data['services'] = $request->input('services', []);
        // Manuel giriş yapıldığında Google'dan alındı işaretini kaldır
        if ($request->has('google_rating') || $request->has('google_review_count')) {
            $data['google_reviews_fetched_at'] = null;
        }
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

        // Firma onayı: checkbox işaretliyse onayla; işaretsiz gönderimde approved_at'e dokunma (yanlışlıkla onayı kaldırmayı önlemek için). Onay kaldırmak için "Reddet" butonu kullanılır.
        if ($request->filled('approved')) {
            $company->update(['approved_at' => now()]);

            if (!$wasApproved && $company->user) {
                SafeNotificationService::sendToUser(
                    $company->user,
                    new CompanyApprovedNotification($company),
                    'Company approved via update'
                );
            }
        }

        // Doğrulama rozetleri: formdaki checkbox'lara göre ayrı ayrı güncellenir (admin gerçekten doğruladığı bilgileri işaretler)
        $company->update([
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
            'phone_verified_at' => $request->boolean('phone_verified') ? now() : null,
            'official_company_verified_at' => $request->boolean('official_company_verified') ? now() : null,
        ]);

        // Nakliyeciye giriş bilgileri e-postası: şifre oluşturma linki ile (nakliyeci linke tıklayıp kendi şifresini belirler)
        if ($request->boolean('send_credentials_email') && $company->user) {
            $user = $company->user;
            $token = Password::broker()->createToken($user);
            $setPasswordUrl = url()->route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
            SafeNotificationService::sendToUser(
                $user,
                new CompanyCredentialsSetPasswordNotification($company, $setPasswordUrl),
                'admin_company_credentials'
            );
            Log::channel('admin_actions')->info('Admin sent company credentials email to mover', [
                'admin_id' => auth()->id(),
                'company_id' => $company->id,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('admin.companies.edit', $company)->with('success', 'Firma güncellendi.');
    }

    /**
     * Google Places API ile firmanın puan ve yorum sayısını Google'dan çekip kaydeder.
     * Google Harita URL'si dolu olmalı; .env'de GOOGLE_PLACES_API_KEY tanımlı olmalı.
     */
    public function fetchGoogleReviews(Company $company)
    {
        $this->authorize('update', $company);

        $url = $company->google_maps_url;
        if (empty($url)) {
            return redirect()->route('admin.companies.edit', $company)
                ->with('error', 'Google Harita URL\'si boş. Önce Harita & Yorumlar sekmesinde Google Harita URL girin.');
        }

        $service = app(GooglePlacesService::class);
        if (! $service->hasApiKey()) {
            return redirect()->route('admin.companies.edit', $company)
                ->with('error', 'Google Places API anahtarı tanımlı değil. .env dosyasına GOOGLE_PLACES_API_KEY ekleyin.');
        }

        $data = $service->fetchRatingAndReviewCount($url);
        if (! $data) {
            return redirect()->route('admin.companies.edit', $company)
                ->with('error', 'Google\'dan puan ve yorum sayısı alınamadı. URL\'yi kontrol edin veya API kotasını inceleyin.');
        }

        $update = ['google_reviews_fetched_at' => now()];
        if (isset($data['rating'])) {
            $update['google_rating'] = $data['rating'];
        }
        if (isset($data['user_ratings_total'])) {
            $update['google_review_count'] = $data['user_ratings_total'];
        }
        $company->update($update);

        return redirect()->route('admin.companies.edit', $company)
            ->with('success', 'Google puan ve yorum sayısı Google\'dan alındı ve kaydedildi.');
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

    public function approve(Request $request, Company $company)
    {
        $this->authorize('update', $company);
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
        
        if ($request->input('redirect') === 'dashboard') {
            return redirect()->route('admin.dashboard')->with('success', 'Firma onaylandı.');
        }
        return redirect()->route('admin.companies.edit', $company)->with('success', 'Firma onaylandı.');
    }

    /** Firma canlı konumunu kaldır; haritada görünürlüğü kapatır. */
    public function removeLocation(Company $company)
    {
        $this->authorize('update', $company);
        $company->update([
            'map_visible' => false,
            'live_latitude' => null,
            'live_longitude' => null,
            'live_location_updated_at' => null,
        ]);
        Log::channel('admin_actions')->info('Admin removed company location', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
        return redirect()->route('admin.companies.edit', $company)
            ->with('success', 'Firma lokasyonu kaldırıldı; haritada görünürlük kapatıldı.');
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
        AdminNotifier::notify('company_rejected', "Firma onayı kaldırıldı: {$company->name}", 'Firma onayı kaldırıldı', ['url' => route('admin.companies.edit', $company)]);
        return back()->with('success', 'Firma onayı kaldırıldı.');
    }

    /** Nakliyecinin gönderdiği bekleyen değişiklikleri onayla; firma yayında kalır, değişiklikler yayına alınır. */
    public function approvePendingChanges(Company $company)
    {
        $this->authorize('update', $company);
        $company->refresh();
        if (! $company->hasPendingChanges()) {
            return redirect()->route('admin.companies.edit', $company)
                ->with('info', 'Bekleyen değişiklik bulunamadı. Zaten onaylanmış olabilir; sayfayı yeniledik.');
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
        return redirect()->route('admin.companies.edit', $company)->with('success', 'Firmanın gönderdiği değişiklikler onaylandı ve yayına alındı.');
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

    /** Admin firma logosu / profil resmi yükler (otomatik onaylı). */
    public function uploadLogo(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'logo.required' => 'Bir logo dosyası seçin.',
            'logo.image' => 'Seçilen dosya resim olmalıdır (JPEG, PNG, JPG veya WebP).',
            'logo.max' => 'Logo en fazla 2 MB olabilir.',
        ]);
        $oldPath = $company->logo;
        $pending = $company->pending_changes;
        $pendingLogoPath = is_array($pending) ? ($pending['logo'] ?? null) : null;
        $path = $request->file('logo')->store('company-logos/' . $company->id, 'public');
        $company->update([
            'logo' => $path,
            'logo_approved_at' => now(),
        ]);
        if (is_array($pending)) {
            unset($pending['logo'], $pending['remove_logo']);
            $company->update(['pending_changes' => empty($pending) ? null : $pending, 'pending_changes_at' => null]);
        }
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        if ($pendingLogoPath && Storage::disk('public')->exists($pendingLogoPath)) {
            Storage::disk('public')->delete($pendingLogoPath);
        }
        Log::channel('admin_actions')->info('Admin uploaded company logo', ['admin_id' => auth()->id(), 'company_id' => $company->id]);
        return back()->with('success', 'Profil resmi / logo yüklendi ve yayına alındı.');
    }

    /** Firma logosunu kaldır. */
    public function removeLogo(Company $company)
    {
        $this->authorize('update', $company);
        $oldPath = $company->logo;
        $pending = $company->pending_changes;
        $pendingLogoPath = is_array($pending) ? ($pending['logo'] ?? null) : null;
        $company->update(['logo' => null, 'logo_approved_at' => null]);
        if (is_array($pending)) {
            unset($pending['logo'], $pending['remove_logo']);
            $company->update(['pending_changes' => empty($pending) ? null : $pending, 'pending_changes_at' => null]);
        }
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        if ($pendingLogoPath && Storage::disk('public')->exists($pendingLogoPath)) {
            Storage::disk('public')->delete($pendingLogoPath);
        }
        Log::channel('admin_actions')->info('Admin removed company logo', ['admin_id' => auth()->id(), 'company_id' => $company->id]);
        return back()->with('success', 'Logo kaldırıldı.');
    }

    /** Nakliyecinin yüklediği bekleyen logoyu yayına alır (sadece logo; diğer bekleyen değişiklikler kalır). */
    public function approvePendingLogo(Company $company)
    {
        $this->authorize('update', $company);
        $company->refresh();
        $pending = $company->pending_changes;
        if (! is_array($pending) || empty($pending['logo'])) {
            return back()->with('error', 'Bekleyen logo bulunamadı. Nakliyeci logoyu panelden yükleyip kaydettiğinde burada görünür.');
        }
        $oldPath = $company->logo;
        $pendingPath = $pending['logo'];
        $company->update([
            'logo' => $pendingPath,
            'logo_approved_at' => now(),
        ]);
        if ($oldPath && $oldPath !== $pendingPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        unset($pending['logo'], $pending['remove_logo']);
        $company->update([
            'pending_changes' => empty($pending) ? null : $pending,
            'pending_changes_at' => empty($pending) ? null : $company->pending_changes_at,
        ]);
        Log::channel('admin_actions')->info('Admin approved pending company logo', ['admin_id' => auth()->id(), 'company_id' => $company->id]);
        return back()->with('success', 'Bekleyen logo yayına alındı. Firma sayfasında görünecek.');
    }

    /** Firma logosunu onayla (mevcut company.logo zaten yüklüyse, sadece onay tarihi güncellenir). */
    public function approveLogo(Company $company)
    {
        $this->authorize('update', $company);
        if (! $company->logo) {
            return back()->with('error', 'Firmada yüklü logo yok. Nakliyeci yeni logo yüklediyse "Bekleyen logoyu yayına al" butonunu kullanın.');
        }
        $company->update(['logo_approved_at' => now()]);
        Log::channel('admin_actions')->info('Admin company logo approved', ['admin_id' => auth()->id(), 'company_id' => $company->id]);
        return back()->with('success', 'Logo onaylandı. Firma sayfasında görünecek.');
    }

    /** Admin galeriye fotoğraf ekler (eklenenler otomatik onaylı). */
    public function storeGallery(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ], [
            'images.required' => 'En az bir fotoğraf seçin.',
            'images.*.image' => 'Seçilen dosyalar resim olmalıdır.',
            'images.*.max' => 'Her fotoğraf en fazla 5 MB olabilir.',
        ]);
        $caption = $request->filled('caption') ? $request->caption : null;
        $maxOrder = (int) $company->vehicleImages()->max('sort_order');
        $uploaded = 0;
        foreach ($request->file('images') as $file) {
            $path = $file->store('company-gallery/' . $company->id, 'public');
            $company->vehicleImages()->create([
                'path' => $path,
                'caption' => $caption,
                'sort_order' => ++$maxOrder,
                'approved_at' => now(),
            ]);
            $uploaded++;
        }
        Log::channel('admin_actions')->info('Admin added gallery images to company', [
            'admin_id' => auth()->id(),
            'company_id' => $company->id,
            'count' => $uploaded,
        ]);
        return back()->with('success', $uploaded === 1 ? 'Galeri fotoğrafı eklendi.' : "{$uploaded} galeri fotoğrafı eklendi.");
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
