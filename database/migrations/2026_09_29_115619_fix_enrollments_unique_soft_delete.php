<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL uses the leftmost column of the old composite unique
        // (student_id) to support the student_id foreign key. We can't
        // drop it while the FK is alive, so: drop FK → swap index → recreate FK.
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_student_id_school_year_id_unique');

            // Give the FK a dedicated index to sit on so it doesn't
            // depend on the composite unique anymore.
            $table->index('student_id', 'enrollments_student_id_index');

            $table->unique(
                ['student_id', 'school_year_id', 'deleted_at'],
                'enrollments_student_year_deleted_unique'
            );
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_student_year_deleted_unique');
            $table->dropIndex('enrollments_student_id_index');

            $table->unique(
                ['student_id', 'school_year_id'],
                'enrollments_student_id_school_year_id_unique'
            );
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students');
        });
    }
};