<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     */
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->afterCommit();  // Only send after database transaction is committed
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = ucfirst($this->order->status);
        return new Envelope(
            subject: "{$status} Order #{$this->order->id} - " . config('app.name'),
            tags: ['order', $this->order->status],
            metadata: [
                'order_id' => $this->order->id,
                'status' => $this->order->status,
                'amount' => $this->order->total_amount,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.status',
            with: [
                'order' => $this->order->load(['user', 'itemOrders.product']),
                'url' => route('orders.show', $this->order->id),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // If the order is completed, attach the invoice PDF
        if ($this->order->status === 'completed') {
            $attachments[] = Attachment::fromPath(
                storage_path("app/invoices/order-{$this->order->id}.pdf")
            )->as("invoice-{$this->order->id}.pdf")
             ->withMime('application/pdf');
        }

        return $attachments;
    }
}