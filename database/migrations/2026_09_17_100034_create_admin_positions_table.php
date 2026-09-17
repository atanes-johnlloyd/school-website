<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. "System Admin", "Registrar"
            $table->text('description')->nullable();
            // Preset copied onto a user's permissions at account-creation time.
            // Editing this later does NOT retroactively change existing admins.
            $table->json('default_permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_positions');
    }
};
