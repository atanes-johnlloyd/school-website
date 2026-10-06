<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // morphs() already creates the (payable_type, payable_id) index
            $table->morphs('payable');

            $table->foreignId('payer_user_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()
                ->constrained('students')->nullOnDelete();

            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('PHP');
            $table->string('reference_no', 32)->unique();

            // PayMongo IDs
            $table->string('paymongo_checkout_id')->nullable()->unique();
            $table->string('paymongo_payment_id')->nullable();
            $table->string('paymongo_payment_intent_id')->nullable();
            $table->text('checkout_url')->nullable();

            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded',
                'partially_refunded',
            ])->default('pending');

            $table->string('payment_method', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->dateTime('expires_at')->nullable();

            // Raw PayMongo JSON for forensics
            $table->json('raw_response')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};