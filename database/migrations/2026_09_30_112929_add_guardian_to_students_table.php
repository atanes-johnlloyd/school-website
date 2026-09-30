<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('guardian_name', 255)->nullable()->after('contact_number');
            $table->string('guardian_contact', 20)->nullable()->after('guardian_name');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['guardian_name', 'guardian_contact']);
        });
    }
};