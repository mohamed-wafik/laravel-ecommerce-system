<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per message in the thread, oldest first. `is_admin_reply`
     * is stored explicitly rather than inferred from `user_id`'s role
     * (roles can change; a reply should permanently reflect who it was
     * from *at the time*, e.g. if an admin is later demoted).
     */
    public function up(): void
    {
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_admin_reply')->default(false);
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
    }
};