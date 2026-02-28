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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('total_amount');
            }

            if (!Schema::hasColumn('orders', 'payment_session_id')) {
                $table->string('payment_session_id')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'payment_intent_id')) {
                $table->string('payment_intent_id')->nullable()->after('payment_session_id');
            }
            
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_intent_id');
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }

            if (Schema::hasColumn('orders', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            
            if (Schema::hasColumn('orders', 'payment_session_id')) {
                $table->dropColumn('payment_session_id');
            }

            if (Schema::hasColumn('orders', 'payment_intent_id')) {
                $table->dropColumn('payment_intent_id');
            }

            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};