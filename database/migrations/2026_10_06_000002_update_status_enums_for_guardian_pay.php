<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. New assignment lifecycle — guardian pays directly
        DB::statement("ALTER TABLE contribution_assignments MODIFY COLUMN status
            ENUM('pending', 'notified', 'paid', 'declined', 'cash_pending', 'overdue', 'waived')
            NOT NULL DEFAULT 'pending'");

        // 2. Guardian action: which path did they choose?
        DB::statement("ALTER TABLE payment_authorizations MODIFY COLUMN action
            ENUM('pay_online', 'pay_cash', 'decline') NULL");

        // 3. Decline reason
        Schema::table('payment_authorizations', function (Blueprint $table) {
            $table->string('decline_reason', 500)->nullable()->after('action_at');
        });

        // 4. Payments need to know which guardian paid
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('guardian_id')->nullable()->after('payer_user_id')
                ->constrained('student_guardians')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('guardian_id');
        });

        Schema::table('payment_authorizations', function (Blueprint $table) {
            $table->dropColumn('decline_reason');
        });

        DB::statement("ALTER TABLE payment_authorizations MODIFY COLUMN action
            ENUM('approved', 'declined') NULL");

        DB::statement("ALTER TABLE contribution_assignments MODIFY COLUMN status
            ENUM('pending', 'awaiting_guardian', 'authorized', 'paid', 'declined', 'waived', 'expired')
            NOT NULL DEFAULT 'pending'");
    }
};