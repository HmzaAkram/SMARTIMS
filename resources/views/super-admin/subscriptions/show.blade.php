@extends('layouts.super-admin')

@section('title', 'Subscription Details - SmartIMS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscription Details</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                           ($subscription->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                           'bg-red-100 text-red-800') }}">
                        <span class="w-2 h-2 rounded-full mr-2 
                            {{ $subscription->status === 'active' ? 'bg-green-500' : 
                               ($subscription->status === 'trialing' ? 'bg-yellow-500' : 
                               'bg-red-500') }}"></span>
                        {{ ucfirst($subscription->status) }}
                    </span>
                    <span class="text-gray-600">
                        <i class="fas fa-building mr-1"></i> {{ $subscription->tenant->name }}
                    </span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @if($subscription->status === 'active')
                <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-ban mr-2"></i> Cancel
                    </button>
                </form>
                @elseif($subscription->status === 'cancelled')
                <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-redo mr-2"></i> Renew
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Subscription Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company</label>
                            <p class="mt-1 text-gray-900">{{ $subscription->tenant->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Plan Name</label>
                            <p class="mt-1 text-gray-900">{{ ucfirst($subscription->plan_name) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Price</label>
                            <p class="mt-1 text-gray-900">${{ $subscription->price }}/month</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($subscription->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Billing Cycle</label>
                            <p class="mt-1 text-gray-900">{{ ucfirst($subscription->billing_cycle) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created On</label>
                            <p class="mt-1 text-gray-900">{{ $subscription->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($subscription->trial_ends_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Trial Ends</label>
                            <p class="mt-1 text-gray-900">{{ $subscription->trial_ends_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($subscription->ends_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subscription Ends</label>
                            <p class="mt-1 text-gray-900">{{ $subscription->ends_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Plan Features -->
            @if($subscription->features)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Plan Features</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($subscription->features as $feature => $value)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($feature) }}</div>
                        <div class="text-lg font-bold text-indigo-600 mt-1">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Payment History -->
            @if($subscription->payments->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($subscription->payments->take(5) as $payment)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $payment->invoice_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">${{ $payment->amount }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs 
                                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    @if($subscription->status === 'active')
                    <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to cancel this subscription?')"
                                class="w-full flex items-center justify-between px-4 py-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-ban text-red-600 mr-3"></i>
                                <span class="text-sm font-medium text-red-800">Cancel Subscription</span>
                            </div>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </button>
                    </form>
                    @elseif($subscription->status === 'cancelled')
                    <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-redo text-green-600 mr-3"></i>
                                <span class="text-sm font-medium text-green-800">Renew Subscription</span>
                            </div>
                            <i class="fas fa-chevron-right text-green-600"></i>
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.payments.create') }}?tenant_id={{ $subscription->tenant_id }}" 
                       class="flex items-center justify-between px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">Add Payment</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    
                    <a href="{{ route('admin.companies.show', $subscription->tenant_id) }}" 
                       class="flex items-center justify-between px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-building text-purple-600 mr-3"></i>
                            <span class="text-sm font-medium text-purple-800">View Company</span>
                        </div>
                        <i class="fas fa-chevron-right text-purple-600"></i>
                    </a>
                    
                    <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this subscription? This action cannot be undone.')"
                                class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-trash text-gray-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-800">Delete Subscription</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-600"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Subscription Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Summary</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Monthly Revenue</span>
                        <span class="text-sm font-medium text-gray-900">${{ $subscription->price }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Annual Revenue</span>
                        <span class="text-sm font-medium text-gray-900">${{ $subscription->price * 12 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Days Active</span>
                        <span class="text-sm font-medium text-gray-900">{{ $subscription->created_at->diffInDays(now()) }} days</span>
                    </div>
                    @if($subscription->ends_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Days Remaining</span>
                        <span class="text-sm font-medium {{ $subscription->ends_at->isPast() ? 'text-red-600' : 'text-green-600' }}">
                            {{ max(0, now()->diffInDays($subscription->ends_at, false)) }} days
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection