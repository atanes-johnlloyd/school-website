<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->foreignId('section_id')->constrained()->restrictOnDelete();
            $table->foreignId('teacher_id')->constrained()->restrictOnDelete();
            $table->foreignId('term_id')->constrained()->restrictOnDelete();
            // Grading weights (must sum to 100) — configurable per subject/class,
            // e.g. Math/Science 40/40/20 vs Languages 25/50/25.
            $table->decimal('weight_written_work', 5, 2)->default(25.00);
            $table->decimal('weight_performance_task', 5, 2)->default(50.00);
            $table->decimal('weight_quarterly_exam', 5, 2)->default(25.00);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['subject_id', 'section_id', 'term_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
