<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_logs', function (Blueprint $table) {
            $table->id();
            $table->string('consent_type', 64)->index(); // kvkk_ihale, kvkk_register, vb.
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ihale_id')->nullable()->constrained('ihaleler')->nullOnDelete();
            $table->json('meta')->nullable(); // ek bağlam (form adı, sayfa vb.)
            $table->timestamp('consented_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_logs');
    }
};
