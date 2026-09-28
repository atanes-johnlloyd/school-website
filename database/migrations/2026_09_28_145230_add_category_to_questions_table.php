<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('subject_id');
            $table->index(['subject_id', 'category'], 'questions_subject_category_idx');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('questions_subject_category_idx');
            $table->dropColumn('category');
        });
    }
};