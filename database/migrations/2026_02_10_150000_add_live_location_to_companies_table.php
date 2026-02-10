<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('live_latitude', 10, 7)->nullable()->after('district');
            $table->decimal('live_longitude', 10, 7)->nullable()->after('live_latitude');
            $table->timestamp('live_location_updated_at')->nullable()->after('live_longitude');
            $table->boolean('map_visible')->default(false)->after('live_location_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['live_latitude', 'live_longitude', 'live_location_updated_at', 'map_visible']);
        });
    }
};
