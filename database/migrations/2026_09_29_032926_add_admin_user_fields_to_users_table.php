<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'disabled'])->default('active')->after('must_change_password');
            $table->text('disabled_reason')->nullable()->after('status');
            $table->string('reset_token', 64)->nullable()->after('disabled_reason');
            $table->timestamp('reset_expiry')->nullable()->after('reset_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'disabled_reason', 'reset_token', 'reset_expiry']);
        });
    }
};