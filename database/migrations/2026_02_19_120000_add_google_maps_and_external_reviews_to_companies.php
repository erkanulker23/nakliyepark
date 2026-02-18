<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('google_maps_url', 500)->nullable()->after('map_visible');
            $table->string('google_reviews_url', 500)->nullable()->after('google_maps_url');
            $table->decimal('google_rating', 2, 1)->nullable()->after('google_reviews_url');
            $table->unsignedInteger('google_review_count')->nullable()->after('google_rating');
            $table->string('yandex_reviews_url', 500)->nullable()->after('google_review_count');
            $table->decimal('yandex_rating', 2, 1)->nullable()->after('yandex_reviews_url');
            $table->unsignedInteger('yandex_review_count')->nullable()->after('yandex_rating');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'google_maps_url', 'google_reviews_url', 'google_rating', 'google_review_count',
                'yandex_reviews_url', 'yandex_rating', 'yandex_review_count',
            ]);
        });
    }
};
