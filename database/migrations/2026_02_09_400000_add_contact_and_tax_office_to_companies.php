<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('phone_2', 20)->nullable()->after('phone');
            $table->string('whatsapp', 20)->nullable()->after('phone_2');
            $table->string('email', 100)->nullable()->after('whatsapp');
            $table->string('tax_office', 100)->nullable()->after('tax_number')->comment('Vergi dairesi / Veri dairesi');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['phone_2', 'whatsapp', 'email', 'tax_office']);
        });
    }
};
