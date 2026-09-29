<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_retake_grants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                  ->constrained('quizzes')
                  ->cascadeOnDelete();

            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete();

            // Who unlocked it
            $table->foreignId('granted_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Why (audit trail)
            $table->text('reason')->nullable();

            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            // Fast lookup for "does this student have a pending grant?"
            $table->index(['quiz_id', 'student_id', 'used_at'], 'idx_pending_grants');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_retake_grants');
    }
};