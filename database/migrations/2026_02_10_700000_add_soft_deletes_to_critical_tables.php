<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('teklifler', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('companies', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('ihaleler', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('teklifler', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('reviews', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('contact_messages', fn (Blueprint $table) => $table->dropSoftDeletes());
    }
};
