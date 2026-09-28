<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->enum('category', ['written_work', 'performance_task', 'quarterly_exam'])
                  ->default('written_work')
                  ->after('title');
        });

        // Spread existing assignments across categories for demo purposes.
        // In production, a teacher would pick the category when creating.
        DB::statement("UPDATE assignments SET category = 'performance_task' WHERE id % 3 = 0");
        DB::statement("UPDATE assignments SET category = 'quarterly_exam'   WHERE id % 5 = 0");
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};