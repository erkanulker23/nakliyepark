<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('blog_categories')->nullOnDelete();
            $table->string('meta_description')->nullable()->after('excerpt');
            $table->boolean('featured')->default(false)->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['meta_description', 'featured']);
        });
        Schema::dropIfExists('blog_categories');
    }
};
