<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pazaryeri_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('vehicle_type', 50); // kamyon, kamyonet, panelvan, tir, lowbed
            $table->string('listing_type', 20)->default('sale'); // sale, rent
            $table->decimal('price', 12, 2)->nullable();
            $table->string('city', 100)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pazaryeri_listings');
    }
};
