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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->restrictOnDelete();
            $table->string('order_number')->unique();
            $table->enum('status', [
                'pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled', 'refunded',
            ])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->foreignUuid('shipping_address_id')->constrained('addresses')->restrictOnDelete();
            $table->foreignUuid('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('idempotency_key')->unique()->comment('client-generated, blocks duplicate order creation');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
