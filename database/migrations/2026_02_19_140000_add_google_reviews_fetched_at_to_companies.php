<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('google_reviews_fetched_at')->nullable()->after('google_review_count')
                ->comment('Puan ve yorum sayısı Google Places API ile alındığı zaman');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('google_reviews_fetched_at');
        });
    }
};
