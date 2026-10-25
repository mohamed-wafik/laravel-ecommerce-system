<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Contact/support requests. `user_id` is nullable — a visitor doesn't
     * need an account to reach support (matches the guest-cart pattern
     * elsewhere in this app: don't force a login for something that
     * shouldn't require one). `name`/`email` are always captured directly
     * on the ticket even for logged-in users, since a support agent
     * replying shouldn't have to join to `users` just to see who to
     * address the reply to, and it preserves the contact info exactly as
     * given even if the account is later deleted/changed.
     */
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};