<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    /**
     * Mesafeli satış sözleşmesi (6502 sayılı Kanun; kredi kartı ile ödeme için gerekli).
     */
    public function mesafeliSatis()
    {
        return view('legal.mesafeli-satis');
    }

    /**
     * Ön bilgilendirme formu (mesafeli satış öncesi bilgilendirme).
     */
    public function onBilgilendirme()
    {
        return view('legal.on-bilgilendirme');
    }

    /**
     * İade ve cayma hakkı.
     */
    public function iadeKosullari()
    {
        return view('legal.iade-kosullari');
    }
}
