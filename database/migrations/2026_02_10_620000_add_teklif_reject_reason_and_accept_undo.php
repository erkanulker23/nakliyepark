<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->after('pending_message'); // admin red gerekçesi (pending update reddi)
            $table->timestamp('accepted_at')->nullable()->after('status'); // teklif kabul zamanı (geri alma süresi için)
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->dropColumn(['reject_reason', 'accepted_at']);
        });
    }
};
