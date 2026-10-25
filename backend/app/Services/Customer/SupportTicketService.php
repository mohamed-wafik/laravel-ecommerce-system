<?php

namespace App\Services\Customer;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Same "every method implicitly scoped to $user" shape as
 * Customer\AddressService/OrderService — a customer can only ever see or
 * reply to their OWN tickets through this class. Includes tickets they
 * opened while logged in (user_id matches) as well as ones later linked
 * to their account (not automatic today, but the query is written to
 * support that if you add email-based claiming later).
 */
class SupportTicketService
{
    public function create(User $user, array $data): SupportTicket
    {
        return SupportTicket::create([
            'user_id' => $user->id,
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);
    }

    public function list(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return SupportTicket::where('user_id', $user->id)->latest()->paginate($perPage);
    }

    public function find(User $user, string $ticketId): SupportTicket
    {
        return SupportTicket::where('user_id', $user->id)->with('replies.user')->findOrFail($ticketId);
    }

    /**
     * A customer follow-up re-opens a resolved/closed ticket back to
     * `in_progress` — replying clearly means the issue isn't actually
     * settled from their side, whatever the previous status said.
     */
    public function reply(User $user, string $ticketId, string $message): SupportTicket
    {
        $ticket = $this->find($user, $ticketId);

        $ticket->replies()->create([
            'user_id' => $user->id,
            'is_admin_reply' => false,
            'message' => $message,
        ]);

        $ticket->update([
            'status' => in_array($ticket->status, ['resolved', 'closed']) ? 'in_progress' : $ticket->status,
            'last_reply_at' => now(),
        ]);

        return $ticket->fresh('replies.user');
    }
}