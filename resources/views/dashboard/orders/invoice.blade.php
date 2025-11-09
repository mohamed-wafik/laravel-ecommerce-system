<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    @vite('resources/css/app.css')
    <style>
        /* Inline Tailwind-like styles for PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            margin: 0;
            padding: 2rem;
        }
        .shadow {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .rounded {
            border-radius: 0.5rem;
        }
        .text-gray {
            color: #6b7280;
        }
        .text-primary {
            color: #2563eb;
        }
        .border {
            border: 1px solid #e5e7eb;
        }
        .bg-gray {
            background-color: #f3f4f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            text-align: left;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="shadow rounded p-6 mb-6 bg-white flex items-center justify-between">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: bold; color: #2563eb;">MyStore</h1>
                <p class="text-gray">Invoice #{{ $order->id }}</p>
            </div>
            <div style="text-align: right;">
                <p class="text-gray"><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p class="text-gray"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            </div>
        </div>
        @if($order->payment_id)
            <div class="text-center mt-10 bg-white p-6 rounded-2xl shadow">
                <h3 class="text-xl font-semibold mb-3 text-gray-800">Payment Information</h3>

                <div class="flex justify-center mt-4">
                    {!! QrCode::size(140)->generate($order->payment_id) !!}
                </div>
            </div>
        @endif
    </div>

    <!-- Customer Info -->
    <div class="shadow rounded p-6 mb-6 bg-white">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Customer Information</h2>
        <p><strong>Name:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>country:</strong> {{ $order->country }}</p>
    </div>

    <!-- Order Items -->
    <div class="shadow rounded p-6 mb-6 bg-white">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Order Details</h2>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->itemOrders as $item)
                    <tr>
                        <td>{{ $item->product->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 1rem;">
            <p style="font-weight: bold;">Total: ${{ number_format($order->total_amount, 2) }}</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-gray" style="font-size: 0.875rem; margin-top: 2rem;">
        <p>Thank you for shopping with <span class="text-primary">laravel app</span>!</p>
        <p>&copy; {{ date('Y') }} MyStore. All rights reserved.</p>
    </div>
</body>
</html>
