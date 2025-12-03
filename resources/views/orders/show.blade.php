@extends('layouts.company')

@section('title', 'Order Details - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('company.dashboard', $tenant) }}" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('company.orders.index', $tenant) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Orders</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Order {{ $order->order_number }}
            </h2>
            <div class="mt-2 flex items-center gap-x-3">
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $order->status_badge }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $order->type_badge }}">
                    {{ ucfirst($order->order_type) }}
                </span>
            </div>
        </div>
        <div class="mt-4 flex gap-x-3 sm:mt-0">
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <a href="{{ route('company.orders.edit', [$tenant, $order]) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Edit Order
            </a>
            @endif
            <button onclick="window.print()" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->item->image)
                                        <img src="{{ $item->item->image }}" alt="{{ $item->item->name }}" class="h-10 w-10 rounded object-cover mr-3">
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->item->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $item->item->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Discount</td>
                                <td class="px-6 py-4 text-right text-sm text-red-600">-${{ number_format($order->discount, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->tax > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Tax</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900">${{ number_format($order->tax, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->shipping_cost > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Shipping</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900">${{ number_format($order->shipping_cost, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t-2 border-gray-300">
                                <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-gray-900">Total</td>
                                <td class="px-6 py-4 text-right text-base font-bold text-indigo-600">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Addresses -->
            @if($order->shipping_address || $order->billing_address)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                @if($order->shipping_address)
                <div class="rounded-lg bg-white shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Shipping Address</h3>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
                @endif

                @if($order->billing_address)
                <div class="rounded-lg bg-white shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Billing Address</h3>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->billing_address }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Notes -->
            @if($order->notes)
            <div class="rounded-lg bg-white shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Notes</h3>
                <p class="text-sm text-gray-600">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Info -->
            <div class="rounded-lg bg-white shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Order Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">Order Date</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->order_date->format('M d, Y') }}</dd>
                    </div>
                    @if($order->delivery_date)
                    <div>
                        <dt class="text-xs text-gray-500">Delivery Date</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_date->format('M d, Y') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Customer Info -->
            <div class="rounded-lg bg-white shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</p>
                        @if($order->customer->email)
                        <p class="text-sm text-gray-500">{{ $order->customer->email }}</p>
                        @endif
                        @if($order->customer->phone)
                        <p class="text-sm text-gray-500">{{ $order->customer->phone }}</p>
                        @endif
                    </div>
                    <a href="{{ route('company.customers.show', [$tenant, $order->customer]) }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        View Customer →
                    </a>
                </div>
            </div>

            <!-- Warehouse Info -->
            @if($order->warehouse)
            <div class="rounded-lg bg-white shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Warehouse</h3>
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-900">{{ $order->warehouse->name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->warehouse->address ?? 'No address' }}</p>
                </div>
            </div>
            @endif

            <!-- Update Status -->
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <div class="rounded-lg bg-white shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Update Status</h3>
                <form method="POST" action="{{ route('company.orders.update', [$tenant, $order]) }}">
                    @csrf
                    @method('PUT')
                    <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-3">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Update Status
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection