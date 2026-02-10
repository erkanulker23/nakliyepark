<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('approved_at');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('official_company_verified_at')->nullable()->after('phone_verified_at');
        });

        // Mevcut onaylı firmalara üç doğrulamayı da approved_at ile doldur (geriye dönük uyumluluk)
        \DB::table('companies')
            ->whereNotNull('approved_at')
            ->update([
                'email_verified_at' => \DB::raw('approved_at'),
                'phone_verified_at' => \DB::raw('approved_at'),
                'official_company_verified_at' => \DB::raw('approved_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'phone_verified_at', 'official_company_verified_at']);
        });
    }
};
