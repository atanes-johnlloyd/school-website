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

        // Collect existing index names so we don't attempt duplicates.
        $existingIndexes = collect(DB::select('SHOW INDEX FROM audit_logs'))
            ->pluck('Key_name')
            ->unique()
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

        // Optional: index the virtual `event` key if you plan to filter by it.
        // Skip silently if the DB doesn't support functional indexes.
        try {
            $hasEventIndex = collect(DB::select(
                "SHOW INDEX FROM audit_logs WHERE Key_name = 'audit_logs_event_index'"
            ))->isNotEmpty();

            if (! $hasEventIndex) {
                DB::statement(
                    "ALTER TABLE audit_logs ADD INDEX audit_logs_event_index ((CAST(new_values->>'$.event' AS CHAR(64))))"
                );
            }
        } catch (\Throwable $e) {
            // Not fatal — the UI just falls back to scanning.
            \Log::info('Skipped audit_logs event index', ['reason' => $e->getMessage()]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['action']);
            $table->dropIndex(['auditable_type', 'auditable_id']);
            $table->dropIndex(['user_id']);
        });
    }
};