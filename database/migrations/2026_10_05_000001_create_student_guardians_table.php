<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('relationship', 50)->nullable();  // mother, father, guardian
            $table->string('contact_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_primary')->default(false);

            // Where the record came from — for auditing / dedupe
            $table->enum('source', ['applicant', 'manual'])->default('manual');
            $table->unsignedBigInteger('source_contact_id')->nullable(); // applicant_contacts.id

            $table->timestamps();

            $table->index(['student_id', 'is_primary']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
    }
};