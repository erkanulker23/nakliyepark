<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yuk_ilanlari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('from_city', 100);
            $table->string('to_city', 100);
            $table->string('load_type', 50)->nullable(); // palet, koli, vb.
            $table->date('load_date')->nullable();
            $table->decimal('volume_m3', 10, 2)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active'); // active, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yuk_ilanlari');
    }
};
