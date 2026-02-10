<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KvkkController extends Controller
{
    /**
     * KVKK Aydınlatma Metni sayfası (ihale formunda link verilir).
     */
    public function aydinlatma()
    {
        return view('kvkk.aydinlatma');
    }
}
