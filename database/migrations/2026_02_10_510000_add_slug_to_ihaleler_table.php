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
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('status');
        });

        $labels = [
            'evden_eve_nakliyat' => 'evden-eve-nakliyat',
            'sehirlerarasi_nakliyat' => 'sehirlerarasi-nakliyat',
            'parca_esya_tasimaciligi' => 'parca-esya',
            'esya_depolama' => 'esya-depolama',
            'ofis_tasima' => 'ofis-tasima',
        ];
        $used = [];
        foreach (DB::table('ihaleler')->get() as $row) {
            $from = Str::slug($row->from_city ?: 'sehir');
            $to = Str::slug($row->to_city ?: 'sehir');
            $service = $labels[$row->service_type ?? 'evden_eve_nakliyat'] ?? 'nakliyat';
            $base = $from . '-' . $to . '-arasi-' . $service . '-ihalesi';
            $slug = $base;
            $n = 0;
            while (isset($used[$slug]) || DB::table('ihaleler')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $n++;
                $slug = $base . '-' . $n;
            }
            $used[$slug] = true;
            DB::table('ihaleler')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('ihaleler', function (Blueprint $table) {
            $table->string('slug', 255)->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
