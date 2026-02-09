<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->date('move_date_end')->nullable()->after('move_date');
        });
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn('move_date_end');
        });
    }
};
