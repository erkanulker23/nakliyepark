<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * İş tamamlandı / iptal / revize tutar ve komisyon kesinleşmesi için.
     */
    public function up(): void
    {
        Schema::create('ihale_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ihale_id')->constrained('ihaleler')->cascadeOnDelete();
            $table->foreignId('teklif_id')->constrained('teklifler')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable(); // işe başlama (opsiyonel)
            $table->timestamp('completed_at')->nullable(); // iş tamamlandı → komisyon kesinleşir
            $table->timestamp('cancelled_at')->nullable(); // iptal (müşteri vazgeçti / firma gelmedi vb.)
            $table->string('cancelled_reason', 100)->nullable();
            $table->decimal('agreed_amount', 12, 2)->nullable(); // kabul edilen teklif tutarı
            $table->decimal('final_amount', 12, 2)->nullable(); // revize / kesinleşen tutar (komisyon buna göre)
            $table->string('status', 30)->default('active')->index(); // active, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ihale_jobs');
    }
};
