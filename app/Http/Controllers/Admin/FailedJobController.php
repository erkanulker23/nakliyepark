<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FailedJobController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('failed_jobs')->orderByDesc('failed_at');
        $failedJobs = $query->paginate(20)->withQueryString();
        $totalCount = DB::table('failed_jobs')->count();
        return view('admin.failed-jobs.index', compact('failedJobs', 'totalCount'));
    }

    public function retry(int $id)
    {
        Artisan::call('queue:retry', ['id' => $id]);
        return back()->with('success', 'İş kuyruğa tekrar alındı.');
    }

    public function retryAll()
    {
        Artisan::call('queue:retry', ['id' => 'all']);
        return back()->with('success', 'Tüm başarısız işler kuyruğa alındı.');
    }

    public function destroy(int $id)
    {
        DB::table('failed_jobs')->where('id', $id)->delete();
        return back()->with('success', 'Kayıt silindi.');
    }

    public function flush()
    {
        Artisan::call('queue:flush');
        return back()->with('success', 'Tüm başarısız işler silindi.');
    }
}
