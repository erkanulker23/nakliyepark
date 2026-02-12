<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocked_phones', function (Blueprint $table) {
            $table->string('normalized_phone', 20)->nullable()->after('phone');
        });

        // Mevcut kayıtları normalize et (sadece rakamlar)
        $rows = \DB::table('blocked_phones')->get();
        foreach ($rows as $row) {
            $normalized = preg_replace('/\D/', '', $row->phone ?? '');
            \DB::table('blocked_phones')->where('id', $row->id)->update(['normalized_phone' => $normalized ?: null]);
        }

        Schema::table('blocked_phones', function (Blueprint $table) {
            $table->index('normalized_phone');
        });
    }

    public function down(): void
    {
        Schema::table('blocked_phones', function (Blueprint $table) {
            $table->dropIndex(['normalized_phone']);
            $table->dropColumn('normalized_phone');
        });
    }
};
