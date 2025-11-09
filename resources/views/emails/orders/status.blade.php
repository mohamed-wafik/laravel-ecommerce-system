@component('mail::message')
# Order Status Update

Hello {{ $order->user->name }},

Your order **#{{ $order->id }}** has been marked as **{{ $order->status }}**.

## Order Details
- **Order Date:** {{ $order->order_date->format('F j, Y') }}
- **Total Amount:** ${{ number_format($order->total_amount, 2) }}
- **Payment Status:** {{ ucfirst($order->payment_status) }}

## Items
@foreach($order->itemOrders as $item)
- {{ $item->product->title }} ({{ $item->quantity }}x) - ${{ number_format($item->subtotal, 2) }}
@endforeach

@if($order->status === 'delivered')
@component('mail::button', ['url' => route('orders.show', $order->id)])
Rate Your Purchase
@endcomponent
@endif

@if($order->payment_status === 'failed' && $order->payment_tsession_id)
@component('mail::button', ['url' => route('payment.retry', $order->id), 'color' => 'red'])
Retry Payment
@endcomponent
@endif

@if($order->status === 'shipped')
Track your order here:
{{ $order->tracking_url ?? 'Tracking information will be available soon.' }}
@endif

Thank you for shopping with **{{ config('app.name') }}**!

If you have any questions, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
