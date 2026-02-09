<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Salon, Mutfak, Yatak Odası, vb.
            $table->decimal('default_volume_m3', 8, 2)->default(10);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_templates');
    }
};
