@extends('layouts.super-admin')

@section('title', 'Edit Payment - SmartIMS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Payment</h1>
            <p class="text-gray-600 mt-1">Update payment information</p>
        </div>

        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Payment Information -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                            <input type="number" name="amount" required step="0.01" min="0"
                                   value="{{ old('amount', $payment->amount) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                <select name="payment_method" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="card" {{ $payment->payment_method == 'card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="bank_transfer" {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="paypal" {{ $payment->payment_method == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                            <input type="date" name="due_date" required
                                   value="{{ old('due_date', $payment->due_date->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Paid At (if completed)</label>
                            <input type="date" name="paid_at"
                                   value="{{ old('paid_at', $payment->paid_at ? $payment->paid_at->format('Y-m-d') : '') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.payments.show', $payment->id) }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection