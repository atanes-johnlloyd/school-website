<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->restrictOnDelete();
            $table->decimal('written_work_score', 5, 2)->nullable();
            $table->decimal('performance_task_score', 5, 2)->nullable();
            $table->decimal('quarterly_exam_score', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->enum('remarks', ['passed', 'failed', 'incomplete'])->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->foreignId('finalized_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'student_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
