<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_estimator_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('from_label', 255)->nullable();
            $table->string('to_label', 255)->nullable();
            $table->unsignedInteger('km')->default(0);
            $table->decimal('price', 12, 2);
            $table->string('room_label', 255)->nullable();
            $table->string('service_type', 64)->nullable();
            $table->string('route_label', 512)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_estimator_calculations');
    }
};
