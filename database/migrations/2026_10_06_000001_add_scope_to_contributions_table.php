<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('scope', ['section', 'class'])
                ->default('section')
                ->after('school_year_id');

            $table->foreignId('section_id')->nullable()
                ->after('class_id')
                ->constrained('sections')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('section_id');
            $table->dropColumn('scope');
        });
    }
};