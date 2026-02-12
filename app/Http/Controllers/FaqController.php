<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $base = Faq::orderBy('sort_order')->orderBy('id');
        $faqsMusteri = (clone $base)->where('audience', 'musteri')->get();
        $faqsNakliyeci = (clone $base)->where('audience', 'nakliyeci')->get();
        return view('faq.index', compact('faqsMusteri', 'faqsNakliyeci'));
    }
}
