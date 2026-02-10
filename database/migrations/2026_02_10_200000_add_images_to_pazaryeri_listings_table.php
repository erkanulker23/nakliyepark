<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pazaryeri_listings', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image_path')->comment('Ek galeri görselleri (path dizisi)');
        });
    }

    public function down(): void
    {
        Schema::table('pazaryeri_listings', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
