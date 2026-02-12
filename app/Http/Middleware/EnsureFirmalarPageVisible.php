<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirmalarPageVisible
{
    /**
     * Nakliye firmaları sayfası admin panelde "gözükmesin" seçiliyse 404 döndürür.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $show = Setting::get('show_firmalar_page', '1');
        if ($show === '0' || $show === 'false') {
            abort(404);
        }

        return $next($request);
    }
}
