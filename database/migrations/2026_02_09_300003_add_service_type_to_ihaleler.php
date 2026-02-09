<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('service_type', 50)->default('evden_eve_nakliyat')->after('user_id');
            $table->string('room_type', 100)->nullable()->after('service_type');
        });
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn(['service_type', 'room_type']);
        });
    }
};
