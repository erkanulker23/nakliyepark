<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ihaleler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('from_city', 100);
            $table->string('from_address')->nullable();
            $table->string('from_postal_code', 10)->nullable();
            $table->string('to_city', 100);
            $table->string('to_address')->nullable();
            $table->string('to_postal_code', 10)->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->date('move_date')->nullable();
            $table->decimal('volume_m3', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('draft'); // draft, published, closed, completed
            $table->timestamps();
        });

        Schema::create('ihale_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ihale_id')->constrained('ihaleler')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ihale_photos');
        Schema::dropIfExists('ihaleler');
    }
};
