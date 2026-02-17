<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DefterApiEntry;
use App\Models\Setting;
use App\Models\User;
use App\Services\DefterApiService;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DefterApiController extends Controller
{
    public function index(Request $request)
    {
        $query = DefterApiEntry::query()->with('company');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('firma', 'like', '%' . $q . '%')
                    ->orWhere('icerik', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%');
            });
        }
        if ($request->filled('imported')) {
            if ($request->imported === '1') {
                $query->whereNotNull('company_id');
            } else {
                $query->whereNull('company_id');
            }
        }

        $entries = $query->latest('updated_at')->paginate(20)->withQueryString();
        $stats = [
            'total' => DefterApiEntry::count(),
            'imported' => DefterApiEntry::whereNotNull('company_id')->count(),
            'not_imported' => DefterApiEntry::whereNull('company_id')->count(),
        ];

        $apiConfigured = DefterApiService::getApiUrl() !== '';
        $settings = [
            'url' => Setting::get('defter_api_url', ''),
            'cookie' => Setting::get('defter_api_cookie', ''),
            'fetch_limit' => (string) (Setting::get('defter_api_fetch_limit', '') ?: 500),
        ];

        return view('admin.defter-api.index', compact('entries', 'stats', 'apiConfigured', 'settings'));
    }

    /**
     * Defter API ayarlarını sayfadan kaydet (URL, cookie, limit).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'defter_api_url' => 'nullable|string|url|max:500',
            'defter_api_cookie' => 'nullable|string|max:2000',
            'defter_api_fetch_limit' => 'nullable|integer|min:10|max:5000',
        ]);

        Setting::set('defter_api_url', $request->input('defter_api_url', ''), 'defter_api');
        Setting::set('defter_api_cookie', $request->input('defter_api_cookie', ''), 'defter_api');
        $limit = $request->input('defter_api_fetch_limit');
        Setting::set('defter_api_fetch_limit', $limit !== null && $limit !== '' ? (int) $limit : 500, 'defter_api');

        return redirect()->route('admin.defter-api.index')->with('success', 'Defter API ayarları kaydedildi.');
    }

    /**
     * Defter API'den veri çek ve tabloya yaz.
     */
    public function fetch(Request $request)
    {
        $result = app(DefterApiService::class)->fetchAndSync();

        if ($result['success']) {
            AdminNotifier::notify('defter_api_fetched', $result['message'], 'Defter API veri çekme', [
                'url' => route('admin.defter-api.index'),
            ]);
            return redirect()->route('admin.defter-api.index')->with('success', $result['message']);
        }

        return redirect()->route('admin.defter-api.index')->with('error', $result['message']);
    }

    /**
     * Tek bir defter kaydını firmaya dönüştür: User (nakliyeci) + Company (onay bekliyor) oluşturur.
     */
    public function importAsCompany(Request $request, DefterApiEntry $entry)
    {
        if ($entry->company_id) {
            return redirect()->route('admin.defter-api.index')->with('error', 'Bu kayıt zaten firmaya aktarılmış.');
        }

        if (trim($entry->firma ?? '') === '') {
            return redirect()->route('admin.defter-api.index')->with('error', 'Firma adı boş, aktarım yapılamıyor.');
        }

        $company = $this->createCompanyFromEntry($entry);

        AdminNotifier::notify('defter_import_company', "Defter'den firma oluşturuldu: {$company->name} (onay bekliyor)", 'Defter import', [
            'url' => route('admin.companies.edit', $company),
        ]);

        return redirect()
            ->route('admin.defter-api.index')
            ->with('success', "«{$company->name}» firması oluşturuldu ve onay bekliyor. Firma onayından sonra yayına alınır.");
    }

    /**
     * Toplu firma aktarımı: seçilen kayıtları firmaya dönüştür.
     */
    public function importSelected(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => ['required', 'integer', Rule::exists('defter_api_entries', 'id')],
        ]);

        $entries = DefterApiEntry::whereNull('company_id')
            ->whereIn('id', $request->ids)
            ->get();

        $created = 0;
        $skipped = 0;
        foreach ($entries as $entry) {
            if ($entry->company_id || trim($entry->firma ?? '') === '') {
                $skipped++;
                continue;
            }
            $this->createCompanyFromEntry($entry);
            $created++;
        }

        $message = "{$created} firma oluşturuldu (onay bekliyor).";
        if ($skipped > 0) {
            $message .= " {$skipped} kayıt atlandı (zaten aktarılmış veya firma adı yok).";
        }

        return redirect()->route('admin.defter-api.index')->with('success', $message);
    }

    /**
     * Tek kayıt için User + Company oluşturur (importAsCompany ve importSelected ortak mantık).
     */
    private function createCompanyFromEntry(DefterApiEntry $entry): Company
    {
        $firmaName = trim($entry->firma ?? 'Firma');
        $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '-', $entry->external_id);
        $email = 'defter-import-' . $safeId . '@nakliyepark.placeholder';
        if (strlen($email) > 255 || User::where('email', $email)->exists()) {
            $email = 'defter-' . $entry->id . '-' . Str::random(4) . '@nakliyepark.placeholder';
        }

        $user = User::create([
            'name' => $firmaName,
            'email' => $email,
            'password' => Hash::make(Str::random(32)),
            'role' => 'nakliyeci',
            'phone' => $entry->phone,
            'email_verified_at' => null,
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'name' => $firmaName,
            'phone' => $entry->phone,
            'phone_2' => null,
            'whatsapp' => $this->normalizeWhatsApp($entry->whatsapp),
            'email' => $entry->email ?: $user->email,
            'address' => null,
            'city' => null,
            'district' => null,
            'description' => $entry->icerik,
            'approved_at' => null,
        ]);

        $entry->update(['company_id' => $company->id]);

        return $company;
    }

    private function normalizeWhatsApp(?string $whatsapp): ?string
    {
        if ($whatsapp === null || $whatsapp === '') {
            return null;
        }
        $n = preg_replace('/\D/', '', $whatsapp);
        return $n !== '' ? $n : null;
    }
}
