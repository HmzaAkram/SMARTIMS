<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} - SMARTIMS</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #4f46e5;
            font-size: 24px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            color: #374151;
            font-size: 28px;
        }
        .invoice-title .invoice-number {
            font-size: 18px;
            color: #6b7280;
            margin-top: 5px;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #374151;
            font-size: 16px;
        }
        .info-content {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
        }
        .info-content p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #4f46e5;
            color: white;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .totals-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .totals-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row.final {
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        @media print {
            .print-button {
                display: none;
            }
            .invoice-container {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Invoice</button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>SMARTIMS</h1>
                <p>{{ $tenant->name ?? 'Company Name' }}</p>
                <p>{{ $tenant->address ?? 'Company Address' }}</p>
                <p>{{ $tenant->phone ?? 'Phone: N/A' }}</p>
                <p>{{ $tenant->email ?? 'Email: N/A' }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $order->order_number }}</div>
                <div style="margin-top: 10px;">
                    @php
                        $statusColor = match($order->status) {
                            'pending' => '#fbbf24',
                            'confirmed' => '#60a5fa',
                            'processing' => '#818cf8',
                            'shipped' => '#a78bfa',
                            'delivered' => '#34d399',
                            'cancelled' => '#f87171',
                            default => '#9ca3af',
                        };
                    @endphp
                    <span class="status-badge" style="background: {{ $statusColor }}; color: white;">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h3>BILL TO</h3>
                <div class="info-content">
                    <p><strong>{{ $order->customer->name ?? 'N/A' }}</strong></p>
                    @if($order->billing_address)
                        <p>{{ nl2br($order->billing_address) }}</p>
                    @endif
                    @if($order->customer)
                        <p>Email: {{ $order->customer->email }}</p>
                        <p>Phone: {{ $order->customer->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="info-box">
                <h3>SHIP TO</h3>
                <div class="info-content">
                    <p><strong>{{ $order->customer->name ?? 'N/A' }}</strong></p>
                    @if($order->shipping_address)
                        <p>{{ nl2br($order->shipping_address) }}</p>
                    @elseif($order->billing_address)
                        <p>{{ nl2br($order->billing_address) }}</p>
                    @else
                        <p>Same as billing address</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="info-section">
            <div class="info-box">
                <h3>INVOICE DETAILS</h3>
                <div class="info-content">
                    <p><strong>Invoice Date:</strong> {{ $order->order_date->format('F d, Y') }}</p>
                    <p><strong>Delivery Date:</strong> {{ $order->delivery_date ? $order->delivery_date->format('F d, Y') : 'Not specified' }}</p>
                    <p><strong>Payment Terms:</strong> {{ $order->payments->first()->payment_terms ?? 'Not specified' }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payments->first()->payment_method ?? 'Not specified') }}</p>
                </div>
            </div>
            <div class="info-box">
                <h3>WAREHOUSE</h3>
                <div class="info-content">
                    <p><strong>{{ $order->warehouse->name ?? 'N/A' }}</strong></p>
                    @if($order->warehouse)
                        <p>{{ $order->warehouse->location }}</p>
                        <p>{{ $order->warehouse->phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item->name ?? 'N/A' }}</strong><br>
                        <small>{{ $item->item->sku ?? '' }}</small>
                    </td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->discount, 2) }}</td>
                    <td>${{ number_format($item->tax, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="totals-box">
                <h3 style="margin-top: 0;">NOTES</h3>
                <p>{{ $order->notes ? nl2br($order->notes) : 'No additional notes.' }}</p>
            </div>
            <div class="totals-box">
                <h3 style="margin-top: 0;">PAYMENT SUMMARY</h3>
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Discount:</span>
                    <span style="color: #ef4444;">-${{ number_format($order->discount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax:</span>
                    <span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping Cost:</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="total-row final">
                    <span>TOTAL AMOUNT:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="total-row" style="margin-top: 20px;">
                    @php
                        $totalPaid = $order->payments->where('status', 'completed')->sum('amount');
                        $balance = $order->total_amount - $totalPaid;
                    @endphp
                    <span>Amount Paid:</span>
                    <span style="color: #10b981;">${{ number_format($totalPaid, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Balance Due:</span>
                    <span style="color: #f59e0b;">${{ number_format($balance, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Invoice generated on {{ now()->format('F d, Y h:i A') }}</p>
            <p>{{ config('app.name') }} - Smart Inventory Management System</p>
        </div>
    </div>

    <script>
        // Auto-print after 1 second (optional)
        window.onload = function() {
            // Uncomment to auto-print
            // setTimeout(function() { window.print(); }, 1000);
        };
    </script>
</body>
</html>