<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Daha önce yüklenmiş ancak admin onayı bekleyen firmaların logolarını
     * onaylı say (logo_approved_at doldur). Böylece firma sayfalarında logo görünsün.
     */
    public function up(): void
    {
        DB::table('companies')
            ->whereNotNull('logo')
            ->where('logo', '!=', '')
            ->whereNull('logo_approved_at')
            ->update(['logo_approved_at' => now()]);
    }

    public function down(): void
    {
        // Geri almak için bu migration ile onaylananları null yapmak güvenli değil (başka onaylar da olabilir). Boş bırakıyoruz.
    }
};
