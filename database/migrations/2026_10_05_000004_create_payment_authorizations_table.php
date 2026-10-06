<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_authorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_assignment_id')
                ->constrained('contribution_assignments')->cascadeOnDelete();
            $table->foreignId('guardian_id')
                ->constrained('student_guardians')->cascadeOnDelete();

            // 64-char hex token used in the email link
            $table->char('token', 64)->unique();

            // null = pending; set on action
            $table->enum('action', ['approved', 'declined'])->nullable();
            $table->timestamp('action_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->dateTime('expires_at');

            $table->timestamps();

            $table->index(['contribution_assignment_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_authorizations');
    }
};