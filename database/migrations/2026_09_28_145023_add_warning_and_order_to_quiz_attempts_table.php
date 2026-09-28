<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('started_at');
            $table->unsignedTinyInteger('warning_count')->default(0)->after('status');
            $table->json('questions_order')->nullable()->after('warning_count');
            $table->json('options_order')->nullable()->after('questions_order');

            // Enforce single attempt per student per quiz
            $table->unique(
                ['quiz_id', 'student_id'],
                'unique_attempt_per_student_per_quiz'
            );
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropUnique('unique_attempt_per_student_per_quiz');
            $table->dropColumn([
                'expires_at',
                'warning_count',
                'questions_order',
                'options_order',
            ]);
        });
    }
};