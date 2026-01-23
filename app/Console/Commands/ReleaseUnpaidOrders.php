<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseUnpaidOrders extends Command
{
    protected $signature = 'orders:release-unpaid';
    protected $description = 'Release product stock for unpaid orders older than 5 minutes';

    public function handle()
    {
        Order::where('payment_status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(5))
            ->with('itemOrders.product')
            ->chunkById(100, function ($orders) {

                DB::transaction(function () use ($orders) {

                    foreach ($orders as $order) {

                        foreach ($order->itemOrders as $item) {
                            if ($item->product) {
                                $item->product->increment('stock', $item->quantity);
                            }
                        }
                        $order->update([
                            'payment_status' => 'expired',
                        ]);
                    }
                });

            });

        $this->info('Unpaid orders stock released successfully.');
    }
}