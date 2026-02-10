<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->json('services')->nullable()->after('description')->comment('Verdiği hizmetler: evden_eve_nakliyat, sehirlerarasi_nakliyat, ofis_tasima, esya_depolama, uluslararasi_nakliyat');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('services');
        });
    }
};
