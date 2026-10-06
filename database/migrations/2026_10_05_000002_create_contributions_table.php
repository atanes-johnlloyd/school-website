<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('school_year_id')->constrained()->restrictOnDelete();

            // Scope — only one of these will typically be set
            $table->foreignId('class_id')->nullable()->constrained('classes')->cascadeOnDelete();
            $table->foreignId('strand_id')->nullable()->constrained('strands')->nullOnDelete();
            $table->enum('grade_level', ['11', '12'])->nullable();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('purpose', 200)->nullable();

            $table->enum('amount_type', ['fixed', 'open'])->default('fixed');
            $table->decimal('amount', 10, 2)->nullable();       // required for 'fixed'
            $table->decimal('min_amount', 10, 2)->nullable();   // optional for 'open'
            $table->decimal('target_amount', 12, 2)->nullable(); // optional goal
            $table->boolean('is_required')->default(false);

            $table->timestamp('deadline_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->boolean('requires_guardian_consent')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'deadline_at']);
            $table->index('school_year_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};