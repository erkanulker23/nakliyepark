<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function edit(Company $company)
    {
        $company->load('user');
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
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
        ]);
        $company->update($request->only([
            'name', 'tax_number', 'tax_office', 'address', 'city', 'district',
            'phone', 'phone_2', 'whatsapp', 'email', 'description',
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
        ]));
        if ($request->has('approved')) {
            $company->update(['approved_at' => $request->boolean('approved') ? now() : null]);
        }
        return redirect()->route('admin.companies.index')->with('success', 'Firma güncellendi.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Firma silindi.');
    }

    public function approve(Company $company)
    {
        $company->update(['approved_at' => now()]);
        AdminNotifier::notify('company_approved', "Firma onaylandı: {$company->name}", 'Firma onayı', ['url' => route('admin.companies.edit', $company)]);
        return back()->with('success', 'Firma onaylandı.');
    }

    public function reject(Company $company)
    {
        $company->update(['approved_at' => null]);
        return back()->with('success', 'Firma onayı kaldırıldı.');
    }
}
