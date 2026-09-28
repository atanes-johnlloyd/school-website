<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 20)->unique();
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->foreignId('strand_id')->nullable()->constrained('strands');
            $table->enum('applicant_type', ['Grade11', 'Grade12', 'Transferee', 'Returning']);

            // Personal info
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('extension_name', 10)->nullable();
            $table->string('lrn', 12);
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('religion', 100)->nullable();
            $table->string('contact_number', 15);
            $table->string('email', 100);

            // Address
            $table->string('house_street', 150)->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('zip_code', 4)->nullable();

            // Previous school
            $table->string('prev_school_name', 150);
            $table->string('prev_school_address', 200)->nullable();
            $table->enum('prev_school_type', ['Public', 'Private', 'International']);
            $table->string('last_school_year', 20)->nullable();

            // Desired
            $table->enum('desired_grade_level', ['11', '12']);

            // Workflow
            $table->enum('status', [
                'pending', 'under_review', 'approved',
                'rejected', 'enrolled', 'needs_resubmission',
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('converted_student_id')->nullable()
                  ->constrained('students')->nullOnDelete();

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['lrn', 'school_year_id'], 'applicants_lrn_year_unique');
            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};