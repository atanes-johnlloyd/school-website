<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->restrictOnDelete();
            $table->foreignId('school_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('section_id')->constrained()->restrictOnDelete();
            $table->enum('status', ['pending', 'enrolled', 'dropped', 'transferred', 'completed'])
                ->default('pending');
            $table->timestamp('enrolled_at')->nullable();
            $table->foreignId('enrolled_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'school_year_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
