<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_supports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Nullable so guests (not logged in) can also submit a ticket
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Optional link to a specific order the ticket is about
            $table->foreignUuid('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            // Snapshot contact info — always present even for guests
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            $table->enum('subject', [
                'order_issue',
                'payment_issue',
                'product_question',
                'complaint',
                'other',
            ])->default('other');

            $table->text('message');

            $table->enum('status', [
                'open',
                'in_progress',
                'resolved',
                'closed',
            ])->default('open');

            $table->text('admin_response')->nullable();

            // Admin user who is handling / handled the ticket
            $table->foreignUuid('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_supports');
    }
};