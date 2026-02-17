<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('view_count')->default(0);
        });
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->unsignedInteger('view_count')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};
