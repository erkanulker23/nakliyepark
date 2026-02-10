<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->decimal('pending_amount', 12, 2)->nullable()->after('message');
            $table->text('pending_message')->nullable()->after('pending_amount');
        });
    }

    public function down(): void
    {
        Schema::table('teklifler', function (Blueprint $table) {
            $table->dropColumn(['pending_amount', 'pending_message']);
        });
    }
};
