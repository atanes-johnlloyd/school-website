<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('strand_id')->nullable()->constrained()->restrictOnDelete();
            $table->enum('grade_level', ['11', '12']);
            $table->string('name'); // e.g. "STEM A"
            $table->foreignId('adviser_id')->nullable()
                ->constrained('teachers')->nullOnDelete();
            $table->unsignedInteger('max_capacity')->default(40);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_year_id', 'grade_level', 'name']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
