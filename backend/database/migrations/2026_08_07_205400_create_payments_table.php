<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('provider', ['stripe'])->default('stripe');
            $table->string('checkout_session_id')->nullable()->unique();
            $table->string('payment_intent_id')->unique()->nullable();
            $table->string('idempotency_key')->unique()->comment('sent as Stripe-Idempotency-Key header');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'succeeded', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable()->comment('card|wallet');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
