<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('guest_contact_name')->nullable()->after('user_id');
            $table->string('guest_contact_email')->nullable()->after('guest_contact_name');
            $table->string('guest_contact_phone', 20)->nullable()->after('guest_contact_email');
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            Schema::getConnection()->statement('ALTER TABLE ihaleler MODIFY user_id BIGINT UNSIGNED NULL');
        }
        if ($driver === 'sqlite') {
            Schema::getConnection()->statement('-- sqlite: user_id stays required; use guest_contact_* for guest ihaleler with a system user');
        }
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropColumn(['guest_contact_name', 'guest_contact_email', 'guest_contact_phone']);
        });
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::getConnection()->statement('ALTER TABLE ihaleler MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
