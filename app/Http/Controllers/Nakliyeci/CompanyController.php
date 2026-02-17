<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function create(Request $request)
    {
        if ($request->user()->company) {
            return redirect()->route('nakliyeci.company.edit');
        }
        return view('nakliyeci.company.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:20',
            'tax_office' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'description' => 'nullable|string',
        ]);

        $company = Company::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'tax_number' => $request->tax_number,
            'tax_office' => $request->tax_office,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'phone' => $request->phone,
            'phone_2' => $request->phone_2,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'description' => $request->description,
            'approved_at' => null, // Admin onayı gerekir; onaylanana kadar sitede görünmez
        ]);
        AdminNotifier::notify('company_created', "Yeni firma: {$company->name} ({$request->user()->email})", 'Yeni firma kaydı', ['url' => route('admin.companies.edit', $company)]);

        return redirect()->route('nakliyeci.dashboard')->with('success', 'Firma bilgileriniz kaydedildi. Admin onayından sonra yayına alınacaktır.');
    }

    public function edit(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create');
        }
        $this->authorize('update', $company);
        $company->load('vehicleImages');
        return view('nakliyeci.company.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create');
        }
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
            'seo_meta_title' => 'nullable|string|max:255',
            'seo_meta_description' => 'nullable|string|max:500',
            'seo_meta_keywords' => 'nullable|string|max:500',
            'services' => 'nullable|array',
            'services.*' => 'nullable|string|in:evden_eve_nakliyat,sehirlerarasi_nakliyat,ofis_tasima,esya_depolama,uluslararasi_nakliyat',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Bekleyen değişikliklere kaydet; ana kayıt ve approved_at değişmez, firma yayında kalır
        $pending = [
            'name' => $request->name,
            'tax_number' => $request->tax_number,
            'tax_office' => $request->tax_office,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'phone' => $request->phone,
            'phone_2' => $request->phone_2,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'description' => $request->description,
            'services' => $request->filled('services') ? $request->services : [],
            'seo_meta_title' => $request->seo_meta_title,
            'seo_meta_description' => $request->seo_meta_description,
            'seo_meta_keywords' => $request->seo_meta_keywords,
        ];

        if ($request->boolean('remove_logo')) {
            if ($company->hasPendingChanges() && !empty($company->pending_changes['logo'])) {
                Storage::disk('public')->delete($company->pending_changes['logo']);
            }
            $pending['remove_logo'] = true;
            $pending['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            $pendingDir = 'company-logos/' . $company->id . '/pending';
            if ($company->hasPendingChanges() && !empty($company->pending_changes['logo'])) {
                Storage::disk('public')->delete($company->pending_changes['logo']);
            }
            $path = $request->file('logo')->store($pendingDir, 'public');
            $pending['logo'] = $path;
        }

        $company->update([
            'pending_changes' => $pending,
            'pending_changes_at' => now(),
        ]);

        AdminNotifier::notify('company_pending_changes', "Firma güncelleme talebi: {$company->name} – Bekleyen değişiklikler onayınızı bekliyor.", 'Bekleyen firma değişiklikleri', ['url' => route('admin.companies.edit', $company)]);

        return redirect()->route('nakliyeci.company.edit')->with('success', 'Değişiklikleriniz kaydedildi. Admin onayından sonra yayına alınacaktır. Firma sayfanız mevcut haliyle yayında.');
    }
}
