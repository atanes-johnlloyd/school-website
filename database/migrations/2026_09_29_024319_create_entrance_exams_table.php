<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrance_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->foreignId('track_id')->nullable()->constrained('tracks')->nullOnDelete();
            $table->string('exam_name', 100);
            $table->date('exam_date');
            $table->time('exam_time');
            $table->string('venue', 150)->nullable();
            $table->unsignedInteger('max_capacity')->default(30);
            $table->enum('grade_level', ['11', '12', 'All'])->default('All');
            $table->enum('status', ['Upcoming', 'Cancelled', 'Completed'])->default('Upcoming');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_year_id', 'exam_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrance_exams');
    }
};