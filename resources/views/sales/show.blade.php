@extends('layouts.company')

@section('title', 'Sales Order ' . $order->order_number . ' - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Sales Order: {{ $order->order_number }}
            </h2>
            <div class="mt-1 flex items-center space-x-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->status_badge }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-sm text-gray-500">Created {{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('company.sales.print.invoice', [$tenant, $order]) }}" target="_blank" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Invoice
            </a>
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <a href="{{ route('company.sales.edit', [$tenant, $order]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Order
            </a>
            @endif
            <a href="{{ route('company.sales.index', $tenant) }}" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                Back to Sales
            </a>
        </div>
    </div>

    <!-- Order Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->item->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->item->sku ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($item->discount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($item->tax, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes || $order->shipping_address || $order->billing_address)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($order->shipping_address)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Shipping Address</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                    @endif
                    
                    @if($order->billing_address)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Billing Address</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->billing_address }}</p>
                    </div>
                    @endif
                    
                    @if($order->notes)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Notes</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Discount</dt>
                            <dd class="text-sm font-medium text-red-600">-${{ number_format($order->discount, 2) }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Tax</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->tax, 2) }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Shipping Cost</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->shipping_cost, 2) }}</dd>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <dt class="text-base font-medium text-gray-900">Total Amount</dt>
                                <dd class="text-base font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Information</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->name ?? 'N/A' }}</dd>
                            @if($order->customer)
                            <dd class="text-sm text-gray-500">{{ $order->customer->email }}</dd>
                            <dd class="text-sm text-gray-500">{{ $order->customer->phone }}</dd>
                            @endif
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Warehouse</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->warehouse->name ?? 'N/A' }}</dd>
                            @if($order->warehouse)
                            <dd class="text-sm text-gray-500">{{ $order->warehouse->location }}</dd>
                            @endif
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->order_date->format('M d, Y') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Delivery Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'Not set' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="mt-1">
                                @php
                                    $totalPaid = $order->payments->where('status', 'completed')->sum('amount');
                                    $paymentStatus = $totalPaid >= $order->total_amount ? 'Paid' : ($totalPaid > 0 ? 'Partial' : 'Pending');
                                    $paymentBadge = match($paymentStatus) {
                                        'Paid' => 'bg-green-100 text-green-800',
                                        'Partial' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-red-100 text-red-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $paymentBadge }}">
                                    {{ $paymentStatus }} (${{ number_format($totalPaid, 2) }} of ${{ number_format($order->total_amount, 2) }})
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Status Management -->
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Update Status</h3>
                </div>
                <div class="p-6">
                    <form id="statusForm" class="space-y-3">
                        @csrf
                        <div>
                            <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="button" onclick="updateStatus()" class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus() {
    const status = document.getElementById('status').value;
    
    fetch('{{ route("company.sales.update.status", [$tenant, $order]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update status');
    });
}
</script>
@endpush
@endsection