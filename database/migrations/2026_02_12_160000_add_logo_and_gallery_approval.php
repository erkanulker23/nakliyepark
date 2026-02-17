<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('logo_approved_at')->nullable()->after('logo');
        });

        Schema::table('company_vehicle_images', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('sort_order');
        });

        // Mevcut logoları onaylı say (geriye dönük uyum)
        \DB::table('companies')->whereNotNull('logo')->whereNull('logo_approved_at')->update(['logo_approved_at' => now()]);
        // Mevcut galeri fotoğraflarını onaylı say
        \DB::table('company_vehicle_images')->whereNull('approved_at')->update(['approved_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('logo_approved_at');
        });
        Schema::table('company_vehicle_images', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
