<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('blocked_at')->nullable()->after('avatar');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('blocked_at')->nullable()->after('approved_at');
        });

        Schema::create('blocked_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('blocked_phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 50)->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
        Schema::table('blocked_ips', function (Blueprint $table) {
            $table->unique('ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('blocked_at');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('blocked_at');
        });
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('blocked_phones');
        Schema::dropIfExists('blocked_emails');
    }
};
