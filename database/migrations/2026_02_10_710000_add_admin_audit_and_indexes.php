<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            if (! Schema::hasColumn('audit_logs', 'actor_type')) {
                Schema::table('audit_logs', function (Blueprint $table) {
                    $table->string('actor_type', 30)->nullable()->after('id');
                });
            }
            if (! Schema::hasColumn('audit_logs', 'actor_id')) {
                Schema::table('audit_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('actor_id')->nullable()->after('actor_type');
                });
            }
            if (! Schema::hasColumn('audit_logs', 'action_reason')) {
                Schema::table('audit_logs', function (Blueprint $table) {
                    $table->text('action_reason')->nullable()->after('new_values');
                });
            }
        }

        if (Schema::hasTable('ihaleler') && ! $this->hasIndex('ihaleler', 'ihaleler_status_index')) {
            Schema::table('ihaleler', fn (Blueprint $table) => $table->index('status'));
        }
        if (Schema::hasTable('teklifler') && ! $this->hasIndex('teklifler', 'teklifler_ihale_company_unique')) {
            Schema::table('teklifler', fn (Blueprint $table) => $table->unique(['ihale_id', 'company_id'], 'teklifler_ihale_company_unique'));
        }
        if (Schema::hasTable('companies') && ! $this->hasIndex('companies', 'companies_approved_at_index')) {
            Schema::table('companies', fn (Blueprint $table) => $table->index('approved_at'));
        }
        if (Schema::hasTable('reviews') && ! $this->hasIndex('reviews', 'reviews_company_id_index')) {
            Schema::table('reviews', fn (Blueprint $table) => $table->index('company_id'));
        }
    }

    private function hasIndex(string $table, string $name): bool
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'mysql') {
            return false;
        }
        $result = DB::selectOne(
            "SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [Schema::getConnection()->getDatabaseName(), $table, $name]
        );
        return $result !== null;
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['actor_type', 'actor_id', 'action_reason']);
        });
        Schema::table('ihaleler', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
        Schema::table('teklifler', function (Blueprint $table) {
            $table->dropUnique('teklifler_ihale_company_unique');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['approved_at']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });
    }
};
