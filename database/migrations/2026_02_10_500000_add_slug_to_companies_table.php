<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('name');
        });

        $used = [];
        foreach (DB::table('companies')->get() as $row) {
            $base = Str::slug($row->name ?: 'firma');
            $slug = $base;
            $n = 0;
            while (isset($used[$slug]) || DB::table('companies')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $n++;
                $slug = $base . '-' . $n;
            }
            $used[$slug] = true;
            DB::table('companies')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('companies', function (Blueprint $table) {
            $table->string('slug', 255)->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
