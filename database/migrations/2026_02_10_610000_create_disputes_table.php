<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ihale_id')->constrained('ihaleler')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('opened_by_user_id')->constrained('users')->cascadeOnDelete(); // müşteri veya nakliyeci
            $table->string('opened_by_type', 20)->default('musteri'); // musteri, nakliyeci
            $table->string('reason', 100)->nullable(); // iptal, adres_hatasi, gelmedi, hakaret, diger
            $table->text('description')->nullable();
            $table->string('status', 30)->default('open')->index(); // open, admin_review, resolved
            $table->text('admin_note')->nullable();
            $table->foreignId('resolved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
