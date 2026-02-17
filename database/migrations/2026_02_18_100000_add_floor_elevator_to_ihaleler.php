<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('from_floor', 50)->nullable()->after('from_neighborhood')->comment('Alınacağı yer: kat (zemin, 1. kat vb.)');
            $table->string('from_elevator', 20)->nullable()->after('from_floor')->comment('Alınacağı yer: asansör var/yok');
            $table->string('to_floor', 50)->nullable()->after('to_neighborhood')->comment('Gideceği yer: kat');
            $table->string('to_elevator', 20)->nullable()->after('to_floor')->comment('Gideceği yer: asansör var/yok');
        });
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn(['from_floor', 'from_elevator', 'to_floor', 'to_elevator']);
        });
    }
};
