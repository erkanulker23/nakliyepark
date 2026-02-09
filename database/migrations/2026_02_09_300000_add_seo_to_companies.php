<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('seo_meta_title')->nullable()->after('description');
            $table->string('seo_meta_description', 500)->nullable()->after('seo_meta_title');
            $table->string('seo_meta_keywords', 500)->nullable()->after('seo_meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['seo_meta_title', 'seo_meta_description', 'seo_meta_keywords']);
        });
    }
};
