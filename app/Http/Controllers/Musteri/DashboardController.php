<?php

namespace App\Http\Controllers\Musteri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ihaleler = $request->user()->ihaleler()->latest()->paginate(10);
        return view('musteri.dashboard', compact('ihaleler'));
    }
}
