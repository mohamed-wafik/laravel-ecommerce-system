<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Support\Arr;

trait RemovesPaymentFields
{
    /**
     * Remove common payment-related fields from a resource array.
     */
    protected function hidePaymentFields(array $data): array
    {
        return Arr::except($data, [
            'payment_status',
            'payment_session_id',
            'payment_id',
            'payment_tsession_id',
            'transaction_id',
            'paid_at',
            'payment_method',
        ]);
    }
}
