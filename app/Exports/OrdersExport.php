<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * ✅ FIXED: Updated to use new schema
     */
    public function collection()
    {
        return Order::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * ✅ FIXED: Map each order to array format
     */
    public function map($order): array
    {
        // ✅ Get products list from items
        $products = $order->items->map(function ($item) {
            return $item->product->title . ' (x' . $item->quantity . ')';
        })->implode(', ');

        // ✅ Handle both registered users and guests
        $customerName = $order->user ? $order->user->name : $order->customer_name;
        $customerEmail = $order->user ? $order->user->email : $order->customer_email;
        $customerPhone = $order->user ? ($order->user->phone ?? $order->customer_phone) : $order->customer_phone;

        return [
            $order->order_number,                           // Order Number
            $customerName,                                  // Customer Name
            $customerEmail,                                 // Email
            $customerPhone,                                 // Phone
            $order->city,                                   // City
            $products,                                      // Products
            ucfirst($order->order_status),                 // Order Status
            ucfirst($order->payment_status),               // Payment Status
            ucfirst($order->payment_method),               // Payment Method
            number_format($order->subtotal, 2),            // Subtotal
            number_format($order->shipping_cost, 2),       // Shipping
            number_format($order->tax, 2),                 // Tax
            number_format($order->discount, 2),            // Discount
            number_format($order->total, 2),               // Total
            $order->created_at->format('Y-m-d H:i:s'),    // Order Date
        ];
    }

    /**
     * ✅ FIXED: Updated headings
     */
    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Email',
            'Phone',
            'City',
            'Products',
            'Order Status',
            'Payment Status',
            'Payment Method',
            'Subtotal',
            'Shipping',
            'Tax',
            'Discount',
            'Total',
            'Order Date',
        ];
    }

    /**
     * ✅ Style the Excel sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}