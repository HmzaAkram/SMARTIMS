@extends('layouts.company')

@section('title', 'Edit Sales Order ' . $order->order_number . ' - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Edit Sales Order: {{ $order->order_number }}
            </h2>
            <div class="mt-1 flex items-center space-x-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->status_badge }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-sm text-gray-500">Created {{ $order->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('company.sales.show', [$tenant, $order]) }}" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Order
            </a>
        </div>
    </div>

    <!-- Sales Order Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('company.sales.update', [$tenant, $order]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Status Update -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Order Status *</label>
                    <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('delivery_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Payment Method</option>
                            <option value="cash" {{ old('payment_method', $order->payments->first()->payment_method ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit_card" {{ old('payment_method', $order->payments->first()->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="bank_transfer" {{ old('payment_method', $order->payments->first()->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method', $order->payments->first()->payment_method ?? '') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="online" {{ old('payment_method', $order->payments->first()->payment_method ?? '') == 'online' ? 'selected' : '' }}>Online Payment</option>
                        </select>
                    </div>
                </div>

                <!-- Payment Terms -->
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700">Payment Terms</label>
                    <input type="text" name="payment_terms" id="payment_terms" value="{{ old('payment_terms', $order->payments->first()->payment_terms ?? '') }}" placeholder="e.g., Net 30" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Order Items Display (Read-only) -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
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
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Subtotal:</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total Amount:</td>
                                    <td class="px-4 py-3 text-base font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Addresses & Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <textarea name="billing_address" id="billing_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('billing_address', $order->billing_address) }}</textarea>
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('company.sales.show', [$tenant, $order]) }}" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Update Sales Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection