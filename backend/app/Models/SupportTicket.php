<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory, HasUuids;

    const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];

    protected $fillable = [
        'user_id', 'name', 'email', 'subject', 'message', 'status', 'last_reply_at',
    ];

    protected function casts(): array
    {
        return [
            'last_reply_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id')->oldest('created_at');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOpenOrInProgress($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }
}
