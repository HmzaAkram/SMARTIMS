@extends('layouts.super-admin')

@section('title', 'Payment Details - SmartIMS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="text-gray-600">
                        <i class="fas fa-file-invoice mr-1"></i> {{ $payment->invoice_number }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 
                           'bg-gray-100 text-gray-800')) }}">
                        <span class="w-2 h-2 rounded-full mr-2 
                            {{ $payment->status === 'completed' ? 'bg-green-500' : 
                               ($payment->status === 'pending' ? 'bg-yellow-500' : 
                               ($payment->status === 'failed' ? 'bg-red-500' : 
                               'bg-gray-500')) }}"></span>
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.payments.edit', $payment->id) }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @if($payment->status === 'pending')
                <form action="{{ route('admin.payments.mark-paid', $payment->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i> Mark as Paid
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.payments.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Invoice Number</label>
                            <p class="mt-1 text-gray-900">{{ $payment->invoice_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Transaction ID</label>
                            <p class="mt-1 text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company</label>
                            <p class="mt-1 text-gray-900">{{ $payment->tenant->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Amount</label>
                            <p class="mt-1 text-gray-900">${{ $payment->amount }} {{ $payment->currency }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Payment Method</label>
                            <p class="mt-1 text-gray-900">{{ ucfirst($payment->payment_method) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Due Date</label>
                            <p class="mt-1 text-gray-900">{{ $payment->due_date->format('M d, Y') }}</p>
                        </div>
                        @if($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Paid At</label>
                            <p class="mt-1 text-gray-900">{{ $payment->paid_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created On</label>
                            <p class="mt-1 text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            @if($payment->metadata)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    @if($payment->status === 'pending')
                    <form action="{{ route('admin.payments.mark-paid', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-between px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span class="text-sm font-medium text-green-800">Mark as Completed</span>
                            </div>
                            <i class="fas fa-chevron-right text-green-600"></i>
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.companies.show', $payment->tenant_id) }}" 
                       class="flex items-center justify-between px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-building text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">View Company</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    
                    <a href="mailto:{{ $payment->tenant->email }}" 
                       class="flex items-center justify-between px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-purple-600 mr-3"></i>
                            <span class="text-sm font-medium text-purple-800">Send Invoice</span>
                        </div>
                        <i class="fas fa-chevron-right text-purple-600"></i>
                    </a>
                    
                    <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')"
                                class="w-full flex items-center justify-between px-4 py-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-trash text-red-600 mr-3"></i>
                                <span class="text-sm font-medium text-red-800">Delete Payment</span>
                            </div>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Company Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ strtoupper(substr($payment->tenant->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->tenant->name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->tenant->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Subscription Plan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->tenant->subscription->plan_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-medium {{ $payment->tenant->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($payment->tenant->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Domain</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->tenant->domain }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection