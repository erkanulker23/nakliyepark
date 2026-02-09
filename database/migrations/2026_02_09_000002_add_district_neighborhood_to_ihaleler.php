<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('from_district', 100)->nullable()->after('from_address');
            $table->string('from_neighborhood', 150)->nullable()->after('from_district');
            $table->string('to_district', 100)->nullable()->after('to_address');
            $table->string('to_neighborhood', 150)->nullable()->after('to_district');
        });
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn(['from_district', 'from_neighborhood', 'to_district', 'to_neighborhood']);
        });
    }
};
