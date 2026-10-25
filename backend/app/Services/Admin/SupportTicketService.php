<?php

namespace App\Services\Admin;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SupportTicketService extends BaseCrudService
{
    protected string $model = SupportTicket::class;

    protected array $with = ['user'];

    public function find(string $id): SupportTicket
    {
        return SupportTicket::with(['user', 'replies.user'])->findOrFail($id);
    }

    public function reply(User $admin, string $ticketId, string $message, ?string $newStatus = null): SupportTicket
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        $ticket->replies()->create([
            'user_id' => $admin->id,
            'is_admin_reply' => true,
            'message' => $message,
        ]);

        $ticket->update([
            // Replying but not choosing a status explicitly is a common
            // case ("just answering a question") — default to in_progress
            // rather than forcing every reply to also be a status decision.
            'status' => $newStatus ?? ($ticket->status === 'open' ? 'in_progress' : $ticket->status),
            'last_reply_at' => now(),
        ]);

        return $ticket->fresh(['user', 'replies.user']);
    }

    public function updateStatus(string $id, string $status): SupportTicket
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $status]);

        return $ticket;
    }

    public function stats(): array
    {
        return [
            'open' => SupportTicket::status('open')->count(),
            'in_progress' => SupportTicket::status('in_progress')->count(),
            'resolved' => SupportTicket::status('resolved')->count(),
            'closed' => SupportTicket::status('closed')->count(),
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('subject', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('name', 'like', "%{$s}%");
            }));
    }
}
