<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    public function collection()
    {
        return Order::with('itemorders.product')->get()->map(function ($order) {
            $products = $order->itemOrders->map(function ($item) {
                return $item->product->title . ' (x' . $item->quantity . ')';
            })->implode(', ');

            return [
                'Order ID' => $order->id,
                "name user" => $order->user->name,
                "country" => $order->country,
                "email" => $order->user->email,
                'Products' => $products,
                'Total' => $order->total_amount,
                'Order Date' => $order->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Order ID', 'Products', 'Total', 'Order Date'];
    }
}