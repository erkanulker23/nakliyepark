<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defter_reklamlari', function (Blueprint $table) {
            $table->id();
            $table->string('baslik')->nullable();
            $table->text('icerik')->nullable(); // HTML veya düz metin
            $table->string('resim')->nullable();
            $table->string('link')->nullable();
            $table->string('konum', 50)->default('sidebar'); // sidebar, ust, alt
            $table->boolean('aktif')->default(true);
            $table->unsignedSmallInteger('sira')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defter_reklamlari');
    }
};
