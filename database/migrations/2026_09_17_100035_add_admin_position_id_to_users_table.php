<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only meaningful when the user holds the "admin" role. Nullable
            // and null-on-delete: losing the position label should never
            // break the account or wipe its already-granted permissions.
            $table->foreignId('admin_position_id')->nullable()
                ->after('must_change_password')
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_position_id');
        });
    }
};
