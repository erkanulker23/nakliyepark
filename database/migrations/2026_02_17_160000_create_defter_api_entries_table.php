<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defter_api_entries', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 64)->unique()->comment('Kaynak sitedeki defter kaydı id');
            $table->string('firma')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_display', 100)->nullable();
            $table->string('whatsapp', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('icerik')->nullable();
            $table->string('profil_url', 500)->nullable();
            $table->string('profil_resmi', 500)->nullable();
            $table->string('tarih', 50)->nullable();
            $table->string('uyelik', 100)->nullable();
            $table->string('uye_tipi', 50)->nullable();
            $table->boolean('cevrimici')->default(false);
            $table->boolean('giris_gerekli')->default(false);
            $table->string('telefon_maskelenmis', 100)->nullable();
            $table->json('raw_data')->nullable()->comment('API’den gelen ham obje');
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('defter_api_entries', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('firma');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defter_api_entries');
    }
};
