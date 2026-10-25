<?php

namespace App\Services\Admin;

use App\Models\Payment;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PaymentService extends BaseCrudService
{
    protected string $model = Payment::class;

    protected array $with = ['order', 'user'];

    public function __construct(
        protected PaymentGatewayFactory $paymentGatewayFactory,
    ) {}


    public function revenueBetween(
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): float {
        return (float) Payment::succeeded()
            ->whereBetween('paid_at', [$from, $to])
            ->sum('amount');
    }

    protected function applyFilters(
        Builder $query,
        array $filters
    ): Builder {
        return $query
            ->when(
                $filters['status'] ?? null,
                fn ($q, $s) => $q->where('status', $s)
            )
            ->when(
                $filters['order_id'] ?? null,
                fn ($q, $o) => $q->where('order_id', $o)
            )
            ->when(
                $filters['provider'] ?? null,
                fn ($q, $p) => $q->where('provider', $p)
            );
    }
}