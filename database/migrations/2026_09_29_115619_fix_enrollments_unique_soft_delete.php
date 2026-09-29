<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'school_year_id']);
        });

        // Partial unique: only one ACTIVE (non-deleted) enrollment per SY
        DB::statement("
            CREATE UNIQUE INDEX uq_active_enrollment_per_sy
            ON enrollments (student_id, school_year_id)
            WHERE deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX uq_active_enrollment_per_sy ON enrollments');

        Schema::table('enrollments', function (Blueprint $table) {
            $table->unique(['student_id', 'school_year_id']);
        });
    }
};