<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_zones', function (Blueprint $table) {
            $table->id();
            $table->string('sayfa', 50); // defter, blog, blog_show, ihale_list, ihale_show, home
            $table->string('konum', 50); // sidebar, ust, alt, icerik_ustu, icerik_alti, icerik_ortasi
            $table->string('baslik')->nullable(); // Admin'de görünen isim
            $table->string('tip', 20)->default('image'); // code | image
            $table->longText('kod')->nullable(); // AdSense / HTML reklam kodu
            $table->string('resim', 500)->nullable(); // Görsel reklam URL
            $table->string('link', 500)->nullable(); // Görsele tıklanınca gidilecek URL
            $table->unsignedSmallInteger('sira')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('ad_zones', function (Blueprint $table) {
            $table->index(['sayfa', 'konum', 'aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_zones');
    }
};
