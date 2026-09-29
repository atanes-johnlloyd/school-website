<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrance_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrance_exam_id')->constrained('entrance_exams')->cascadeOnDelete();
            $table->foreignId('applicant_id')->constrained('applicants')->cascadeOnDelete();
            $table->decimal('score', 6, 2)->nullable();
            $table->enum('result', ['Pending', 'Passed', 'Failed', 'Absent', 'For Interview'])
                  ->default('Pending');
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->unique(['entrance_exam_id', 'applicant_id'], 'uq_applicant_per_exam');
            $table->index('applicant_id');
            $table->index('result');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrance_exam_results');
    }
};