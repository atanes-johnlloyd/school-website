<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->decimal('amount_owed', 10, 2);

            $table->enum('status', [
                'pending',            // assigned, nothing done yet
                'awaiting_guardian',  // consent email sent, waiting for reply
                'authorized',         // guardian approved — student can pay
                'paid',               // payment confirmed
                'declined',           // guardian declined
                'waived',             // teacher/admin waived it
                'expired',            // deadline passed unpaid
            ])->default('pending');

            $table->foreignId('guardian_id')->nullable()
                ->constrained('student_guardians')->nullOnDelete();

            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->string('decline_reason', 500)->nullable();
            $table->timestamp('paid_at')->nullable();

            // Consent email tracking
            $table->unsignedTinyInteger('consent_resend_count')->default(0);
            $table->timestamp('last_consent_sent_at')->nullable();

            $table->timestamps();

            $table->unique(['contribution_id', 'student_id'], 'uq_contribution_student');
            $table->index(['student_id', 'status']);
            $table->index(['contribution_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_assignments');
    }
};