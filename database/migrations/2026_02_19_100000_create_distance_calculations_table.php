<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distance_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('from_label', 255);
            $table->string('to_label', 255);
            $table->unsignedInteger('km');
            $table->string('route_label', 512)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distance_calculations');
    }
};
