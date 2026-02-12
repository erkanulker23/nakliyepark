<?php

namespace App\Http\Controllers\Nakliyeci;

use App\Http\Controllers\Controller;
use App\Models\DefterYaniti;
use App\Models\YukIlani;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    /** Firma başına en fazla aktif defter ilanı (istismar önlemi) */
    public const MAX_AKTIF_ILAN = 300;
    public function index(Request $request)
    {
        $ilanlar = YukIlani::with('company')
            ->where('status', 'active')
            ->where('company_id', '!=', $request->user()->company?->id ?? 0)
            ->latest()
            ->paginate(15);

        return view('nakliyeci.ledger', compact('ilanlar'));
    }

    public function create(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')
                ->with('error', 'Deftere yazmak için önce firma bilgilerinizi oluşturmalısınız.');
        }
        if (! $company->isApproved()) {
            return redirect()->route('nakliyeci.company.edit')
                ->with('error', 'Deftere yazmak için firmanızın admin tarafından onaylanmış olması gerekir. Firma bilgilerinizi tamamlayıp onay bekleyin.');
        }
        $aktifSayisi = $company->yukIlanlari()->where('status', 'active')->count();
        if ($aktifSayisi >= self::MAX_AKTIF_ILAN) {
            return redirect()->route('nakliyeci.ledger')->with('error', 'Firmanızın aktif defter ilanı limitine (' . self::MAX_AKTIF_ILAN . ') ulaştınız. Eski ilanları pasife alıp tekrar deneyin.');
        }
        return view('nakliyeci.ledger-create');
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')
                ->with('error', 'Deftere yazmak için önce firma bilgilerinizi oluşturmalısınız.');
        }
        if (! $company->isApproved()) {
            return redirect()->route('nakliyeci.company.edit')
                ->with('error', 'Deftere yazmak için firmanızın onaylanmış olması gerekir.');
        }
        $aktifSayisi = $company->yukIlanlari()->where('status', 'active')->count();
        if ($aktifSayisi >= self::MAX_AKTIF_ILAN) {
            return redirect()->back()->withInput()->with('error', 'Firmanızın aktif defter ilanı limitine (' . self::MAX_AKTIF_ILAN . ') ulaştınız. Yeni ilan ekleyemezsiniz.');
        }
        $data = $request->validate([
            'from_city' => 'required|string|max:100',
            'to_city' => 'required|string|max:100',
            'load_type' => 'nullable|string|max:50',
            'load_date' => 'nullable|date',
            'volume_m3' => 'nullable|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $data['company_id'] = $company->id;
        $data['status'] = 'active';
        YukIlani::create($data);
        if ($request->input('redirect_to') === 'defter') {
            return redirect()->route('defter.index')->with('success', 'Deftere yazıldı. İlanınız yayında.');
        }
        return redirect()->route('nakliyeci.ledger')->with('success', 'Deftere yazıldı. İlanınız yayında.');
    }

    /** Mevcut bir defter ilanına (yük ilanına) yanıt yaz */
    public function storeReply(Request $request, YukIlani $yukIlani)
    {
        $company = $request->user()->company;
        if (! $company) {
            return redirect()->route('nakliyeci.company.create')
                ->with('error', 'Defter ilanına yanıt vermek için önce firma bilgilerinizi oluşturmalısınız.');
        }
        if (! $company->isApproved()) {
            return redirect()->route('nakliyeci.company.edit')
                ->with('error', 'Defter ilanına yanıt vermek için firmanızın onaylanmış olması gerekir.');
        }
        if ($yukIlani->company_id === $company->id) {
            return redirect()->back()->with('error', 'Kendi ilanınıza yanıt yazamazsınız.');
        }
        if ($yukIlani->status !== 'active') {
            return redirect()->back()->with('error', 'Bu ilan artık yanıt kabul etmiyor.');
        }

        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        DefterYaniti::create([
            'yuk_ilani_id' => $yukIlani->id,
            'company_id' => $company->id,
            'body' => $data['body'],
        ]);

        return redirect()->back()->with('success', 'Yanıtınız gönderildi.');
    }
}
