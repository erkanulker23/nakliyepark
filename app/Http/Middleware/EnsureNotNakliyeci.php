<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotNakliyeci
{
    /**
     * Nakliye firması kullanıcılarının ihale oluşturma sayfasına girmesini engeller.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'nakliyeci') {
            return redirect()->route('home')
                ->with('info', 'İhale oluşturma sayfası sadece taşınacak müşteriler içindir. Nakliye firması olarak giriş yaptınız; ihalelere teklif vermek için nakliyeci panelinden ihalelere göz atabilirsiniz.');
        }

        return $next($request);
    }
}
