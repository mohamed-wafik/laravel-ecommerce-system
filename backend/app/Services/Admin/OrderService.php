<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService extends BaseCrudService
{
    protected string $model = Order::class;

    protected array $with = [
        'user',
        'items.product',
        'items.variant',
        'payment',
        'shippingAddress',
        'billingAddress',
    ];

    /**
     * Valid order lifecycle transitions.
     *
     * Refund is intentionally NOT included here.
     * Refund is a financial operation, not a normal status change.
     */
    protected array $transitions = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['paid', 'cancelled'],
        'paid' => ['shipped'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
        'refunded' => [],
    ];

    public function __construct(
        protected PaymentGatewayFactory $paymentGatewayFactory,
    ) {}

    public function updateStatus(
        string $id,
        string $newStatus
    ): Order {
        return DB::transaction(function () use ($id, $newStatus) {

            /** @var Order $order */
            $order = Order::lockForUpdate()->findOrFail($id);

            if (! in_array(
                $newStatus,
                $this->transitions[$order->status] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => sprintf(
                        'Cannot transition order from %s to %s.',
                        $order->status,
                        $newStatus
                    ),
                ]);
            }

            $order->update([
                'status' => $newStatus,
            ]);

            if ($newStatus === 'cancelled') {
                $this->restockItems($order);
            }

            return $order->fresh($this->with);
        });
    }

    public function refund(string $id): Order
    {
        return DB::transaction(function () use ($id) {

            /** @var Order $order */
            $order = Order::with([
                'payment',
                'items.product',
                'items.variant',
            ])
                ->lockForUpdate()
                ->findOrFail($id);

            if (! in_array($order->status, [
                'paid',
                'shipped',
                'delivered',
            ], true)) {
                throw ValidationException::withMessages([
                    'order' => 'This order cannot be refunded.',
                ]);
            }

            $payment = $order->payment;

            if (! $payment) {
                throw ValidationException::withMessages([
                    'payment' => 'This order does not have a payment.',
                ]);
            }

            if ($payment->status === 'refunded') {
                return $order->fresh($this->with);
            }

            if ($payment->status !== 'succeeded') {
                throw ValidationException::withMessages([
                    'payment' => 'Only successful payments can be refunded.',
                ]);
            }

            if (! $payment->payment_intent_id) {
                throw ValidationException::withMessages([
                    'payment' => 'Payment does not have a payment intent.',
                ]);
            }

            $gateway = $this->paymentGatewayFactory->make(
                $payment->provider
            );

            $gateway->refundPayment(
                paymentIntentId: $payment->payment_intent_id,
                amount: null,
                metadata: [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
                idempotency_key: "refund-{$payment->id}",
            );

            $payment->update([
                'status' => 'refunded',
            ]);

            $order->update([
                'status' => 'refunded',
            ]);

            return $order->fresh($this->with);
        });
    }

    protected function restockItems(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $item->variant?->increment(
                    'stock_quantity',
                    $item->quantity
                );

                continue;
            }

            $item->product?->increment(
                'stock_quantity',
                $item->quantity
            );
        }
    }

    public function stats(): array
    {
        return [
            'pending' => Order::status('pending')->count(),
            'processing' => Order::status('processing')->count(),
            'paid' => Order::status('paid')->count(),
            'shipped' => Order::status('shipped')->count(),
            'delivered' => Order::status('delivered')->count(),
            'cancelled' => Order::status('cancelled')->count(),
            'refunded' => Order::status('refunded')->count(),

            'revenue' => (float) Order::whereIn('status', [
                'paid',
                'shipped',
                'delivered',
            ])->sum('total'),
        ];
    }

    protected function applyFilters(
        Builder $query,
        array $filters
    ): Builder {
        return $query
            ->when(
                $filters['search'] ?? null,
                fn ($q, $search) => $q->where(
                    'order_number',
                    'like',
                    "%{$search}%"
                )
            )
            ->when(
                $filters['status'] ?? null,
                fn ($q, $status) => $q->where(
                    'status',
                    $status
                )
            )
            ->when(
                $filters['user_id'] ?? null,
                fn ($q, $userId) => $q->where(
                    'user_id',
                    $userId
                )
            )
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'created_at',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'created_at',
                    '<=',
                    $date
                )
            );
    }
}