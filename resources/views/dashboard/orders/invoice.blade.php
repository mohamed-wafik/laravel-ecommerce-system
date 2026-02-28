<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        /* Inline Tailwind-like styles for PDF */
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            margin: 0;
            padding: 2rem;
            line-height: 1.6;
        }
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid #2563eb;
        }
        .company-info h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 0.5rem 0;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #374151;
            margin: 0 0 0.5rem 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }
        .info-section {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .info-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-section p {
            margin: 0.5rem 0;
            color: #4b5563;
        }
        .info-section strong {
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }
        thead {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
        }
        th {
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        th:last-child, td:last-child {
            text-align: right;
        }
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem;
            color: #374151;
        }
        tbody tr:hover {
            background-color: #f9fafb;
        }
        .totals-section {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }
        .totals-table {
            width: 400px;
        }
        .totals-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-table td {
            padding: 0.75rem 1rem;
        }
        .totals-table .total-row {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 1.125rem;
            border-top: 2px solid #2563eb;
        }
        .totals-table .total-row td {
            color: #1f2937;
        }
        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e0e7ff; color: #4338ca; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .payment-pending { background: #fef3c7; color: #92400e; }
        .payment-paid { background: #d1fae5; color: #065f46; }
        .payment-failed { background: #fee2e2; color: #991b1b; }
        .qr-section {
            text-align: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 2px dashed #d1d5db;
        }
        .footer {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.875rem;
        }
        .footer .brand {
            color: #2563eb;
            font-weight: 600;
        }
        .print-button {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 0.75rem 1.5rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }
        .print-button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    {{-- Print Button --}}
    <button onclick="window.print()" class="print-button no-print">
        <i class="fa-solid fa-print"></i> Print Invoice
    </button>

    <div class="invoice-container">
        {{-- Header --}}
        <div class="header">
            <div class="company-info">
                <h1>MyStore</h1>
                <p style="margin: 0; color: #6b7280;">123 Business Street</p>
                <p style="margin: 0; color: #6b7280;">City, State 12345</p>
                <p style="margin: 0; color: #6b7280;">Phone: (123) 456-7890</p>
                <p style="margin: 0; color: #6b7280;">Email: contact@mystore.com</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                {{-- ✅ FIXED: استخدام order_number --}}
                <p style="margin: 0.25rem 0; color: #6b7280;">
                    <strong>Invoice #:</strong> {{ $order->order_number }}
                </p>
                <p style="margin: 0.25rem 0; color: #6b7280;">
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}
                </p>
                <p style="margin: 0.25rem 0; color: #6b7280;">
                    <strong>Time:</strong> {{ $order->created_at->format('h:i A') }}
                </p>
            </div>
        </div>

        {{-- Status Badges --}}
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem;">
            {{-- ✅ FIXED: استخدام order_status --}}
            <div>
                <span style="font-weight: 600; color: #4b5563; margin-right: 0.5rem;">Order Status:</span>
                <span class="status-badge status-{{ $order->order_status }}">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
            {{-- ✅ NEW: Payment Status --}}
            <div>
                <span style="font-weight: 600; color: #4b5563; margin-right: 0.5rem;">Payment Status:</span>
                <span class="status-badge payment-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>

        {{-- Customer & Shipping Info --}}
        <div class="info-grid">
            {{-- Customer Information --}}
            <div class="info-section">
                <h3>Bill To</h3>
                {{-- ✅ FIXED: Support for both registered users and guests --}}
                @if($order->user)
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                @else
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                @endif
            </div>

            {{-- Shipping Information --}}
            <div class="info-section">
                <h3>Ship To</h3>
                {{-- ✅ FIXED: استخدام shipping_address من order --}}
                <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                <p><strong>City:</strong> {{ $order->city }}</p>
                @if($order->postal_code)
                    <p><strong>Postal Code:</strong> {{ $order->postal_code }}</p>
                @endif
                {{-- ✅ NEW: Shipping Method --}}
                <p><strong>Shipping Method:</strong> {{ ucfirst($order->shipping_method) }}</p>
            </div>
        </div>

        {{-- Payment Method --}}
        <div class="info-section" style="margin-bottom: 2rem;">
            <h3>Payment Information</h3>
            {{-- ✅ NEW: Payment Method --}}
            <p>
                <strong>Payment Method:</strong> 
                @if($order->payment_method === 'card')
                    Credit/Debit Card
                @elseif($order->payment_method === 'cod')
                    Cash on Delivery
                @elseif($order->payment_method === 'wallet')
                    Mobile Wallet
                @else
                    {{ ucfirst($order->payment_method) }}
                @endif
            </p>
            @if($order->stripe_payment_intent)
                <p><strong>Transaction ID:</strong> {{ $order->stripe_payment_intent }}</p>
            @endif
        </div>

        {{-- Order Items Table --}}
        <div style="margin: 2rem 0;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">
                Order Details
            </h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th style="width: 15%; text-align: center;">Quantity</th>
                        <th style="width: 17.5%;">Unit Price</th>
                        <th style="width: 17.5%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- ✅ FIXED: استخدام items بدلاً من itemOrders --}}
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product->title }}</strong>
                                @if($item->product->sku)
                                    <br><small style="color: #6b7280;">SKU: {{ $item->product->sku }}</small>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            {{-- ✅ FIXED: استخدام price من item --}}
                            <td>${{ number_format($item->price, 2) }}</td>
                            {{-- ✅ FIXED: استخدام total من item --}}
                            <td>${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals Section --}}
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    {{-- ✅ FIXED: استخدام subtotal --}}
                    <td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping ({{ ucfirst($order->shipping_method) }}):</td>
                    {{-- ✅ FIXED: استخدام shipping_cost --}}
                    <td style="text-align: right;">${{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax (14%):</td>
                    {{-- ✅ FIXED: استخدام tax --}}
                    <td style="text-align: right;">${{ number_format($order->tax, 2) }}</td>
                </tr>
                {{-- ✅ NEW: Discount if applicable --}}
                @if($order->discount > 0)
                <tr>
                    <td style="color: #059669;">Discount:</td>
                    <td style="text-align: right; color: #059669;">-${{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    {{-- ✅ FIXED: استخدام total --}}
                    <td style="text-align: right;">${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- QR Code Section (if payment_id exists) --}}
        @if($order->stripe_session_id || $order->stripe_payment_intent)
        <div class="qr-section">
            <h3 style="margin: 0 0 1rem 0; color: #1f2937;">Payment Verification</h3>
            <div style="display: inline-block;">
                {{-- You can use a QR code package here --}}
                @if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode'))
                    {!! QrCode::size(150)->generate($order->stripe_payment_intent ?? $order->stripe_session_id) !!}
                @else
                    <p style="color: #6b7280; margin: 0;">
                        Payment ID: {{ $order->stripe_payment_intent ?? $order->stripe_session_id }}
                    </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Notes Section --}}
        @if($order->notes ?? false)
        <div class="info-section" style="margin-top: 2rem;">
            <h3>Order Notes</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        {{-- Terms & Conditions --}}
        <div style="margin-top: 2rem; padding: 1.5rem; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.5rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #92400e; font-size: 1rem;">Terms & Conditions</h4>
            <p style="margin: 0; color: #78350f; font-size: 0.875rem; line-height: 1.5;">
                Payment is due within 30 days. Please include the invoice number on your check.
                All items remain the property of MyStore until payment is received in full.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p style="margin: 0.5rem 0;">
                Thank you for shopping with <span class="brand">MyStore</span>!
            </p>
            <p style="margin: 0.5rem 0;">
                Questions? Contact us at: support@mystore.com | (123) 456-7890
            </p>
            <p style="margin: 0.5rem 0;">
                &copy; {{ date('Y') }} MyStore. All rights reserved.
            </p>
        </div>
    </div>

    {{-- Font Awesome for icons (if needed) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</body>
</html>