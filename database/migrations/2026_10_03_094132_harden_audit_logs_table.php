<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        // ✅ FIX: Use Laravel's cross-database schema introspection instead of `SHOW INDEX`.
        $existingIndexes = collect(Schema::getIndexes('audit_logs'))
            ->pluck('name')
            ->all();

        Schema::table('audit_logs', function (Blueprint $table) use ($existingIndexes) {
            if (! in_array('audit_logs_created_at_index', $existingIndexes, true)) {
                $table->index('created_at');
            }
            if (! in_array('audit_logs_action_index', $existingIndexes, true)) {
                $table->index('action');
            }
            if (! in_array('audit_logs_auditable_type_auditable_id_index', $existingIndexes, true)) {
                $table->index(['auditable_type', 'auditable_id']);
            }
            if (! in_array('audit_logs_user_id_index', $existingIndexes, true)) {
                $table->index('user_id');
            }
        });

        // ✅ FIX: Functional/expression index is MySQL-only. Guard behind a driver check
        // so SQLite (test suite) silently skips it.
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                $hasEventIndex = ! empty(DB::select(
                    "SHOW INDEX FROM audit_logs WHERE Key_name = 'audit_logs_event_index'"
                ));

                if (! $hasEventIndex) {
                    DB::statement(
                        "ALTER TABLE audit_logs ADD INDEX audit_logs_event_index ((CAST(new_values->>'$.event' AS CHAR(64))))"
                    );
                }
            } catch (\Throwable $e) {
                \Log::info('Skipped audit_logs event index', ['reason' => $e->getMessage()]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        // ✅ FIX: Only drop indexes that actually exist to avoid errors
        $existingIndexes = collect(Schema::getIndexes('audit_logs'))->pluck('name')->all();

        Schema::table('audit_logs', function (Blueprint $table) use ($existingIndexes) {
            if (in_array('audit_logs_created_at_index', $existingIndexes, true)) {
                $table->dropIndex(['created_at']);
            }
            if (in_array('audit_logs_action_index', $existingIndexes, true)) {
                $table->dropIndex(['action']);
            }
            if (in_array('audit_logs_auditable_type_auditable_id_index', $existingIndexes, true)) {
                $table->dropIndex(['auditable_type', 'auditable_id']);
            }
            if (in_array('audit_logs_user_id_index', $existingIndexes, true)) {
                $table->dropIndex(['user_id']);
            }
            if (in_array('audit_logs_event_index', $existingIndexes, true)) {
                $table->dropIndex('audit_logs_event_index');
            }
        });
    }
};