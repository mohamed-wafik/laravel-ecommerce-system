<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactSupportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'admin_response' => $this->admin_response,
            'order' => $this->whenLoaded('order', fn () => [
                'id' => $this->order?->id,
                'order_number' => $this->order?->order_number,
            ]),
            'user' => $this->whenLoaded('user', fn () => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null),
            'handler' => $this->whenLoaded('handler', fn () => $this->handler ? [
                'id' => $this->handler->id,
                'name' => $this->handler->name,
            ] : null),
            'resolved_at' => $this->resolved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}