<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('strand_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('grade_level', ['11', '12', 'both'])->default('both');
            $table->boolean('is_core')->default(false);
            $table->unsignedInteger('hours')->default(80);
            $table->foreignId('prerequisite_subject_id')->nullable()
                ->constrained('subjects')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
