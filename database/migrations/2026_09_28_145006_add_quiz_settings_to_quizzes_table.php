<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('show_score_immediately')->default(true)->after('is_published');
            $table->boolean('show_correct_answers')->default(true)->after('show_score_immediately');
            $table->boolean('show_explanations')->default(true)->after('show_correct_answers');
            $table->boolean('shuffle_options')->default(false)->after('shuffle_questions');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'show_score_immediately',
                'show_correct_answers',
                'show_explanations',
                'shuffle_options',
            ]);
        });
    }
};