<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('name');
            $table->string('icon', 50)->nullable()->after('image_path');
            $table->string('color', 20)->nullable()->after('icon');   // for UI tinting
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('name');
            $table->string('image_path')->nullable()->after('icon');
            $table->string('color', 20)->nullable()->after('image_path');
        });

        Schema::table('strands', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('name');
            $table->string('image_path')->nullable()->after('icon');
            $table->string('color', 20)->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'icon', 'color']);
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'icon', 'color']);
        });
        Schema::table('strands', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'icon', 'color']);
        });
    }
};  