<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->string('meta_description')->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('blog_posts', 'featured')) {
                $table->boolean('featured')->default(false)->after('published_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'featured']);
        });
    }
};
