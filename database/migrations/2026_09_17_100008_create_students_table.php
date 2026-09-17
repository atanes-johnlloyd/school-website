<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('lrn', 12)->unique();
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('contact_number', 20)->nullable();
            $table->string('house_street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code', 4)->nullable();
            $table->enum('status', ['active', 'graduated', 'dropped_out', 'transferred_out'])
                ->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
