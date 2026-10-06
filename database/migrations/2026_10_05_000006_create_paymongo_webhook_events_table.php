<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paymongo_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();  // PayMongo's evt_xxx
            $table->string('type', 100);           // e.g. checkout_session.payment.paid
            $table->json('payload');
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->text('error')->nullable();

            $table->index('type');
            $table->index('processed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paymongo_webhook_events');
    }
};