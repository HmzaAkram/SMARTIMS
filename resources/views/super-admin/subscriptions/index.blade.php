@extends('layouts.super-admin')

@section('title', 'Subscriptions Management - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscriptions Management</h1>
                <p class="text-gray-600 mt-1">Manage all company subscriptions</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.subscriptions.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    <i class="fas fa-plus mr-2"></i> Add Subscription
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Subscriptions</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Trialing</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['trialing'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Monthly Revenue</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($stats['revenue']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search companies..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>Trialing</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                <select name="plan" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Plans</option>
                    <option value="starter" {{ request('plan') == 'starter' ? 'selected' : '' }}>Starter</option>
                    <option value="growth" {{ request('plan') == 'growth' ? 'selected' : '' }}>Growth</option>
                    <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="enterprise" {{ request('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="w-full md:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Billing Cycle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-bold">{{ strtoupper(substr($subscription->tenant->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $subscription->tenant->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $subscription->tenant->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $subscription->plan_name === 'enterprise' ? 'bg-purple-100 text-purple-800' : 
                                   ($subscription->plan_name === 'premium' ? 'bg-blue-100 text-blue-800' : 
                                   ($subscription->plan_name === 'growth' ? 'bg-green-100 text-green-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($subscription->plan_name) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($subscription->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                <span class="w-2 h-2 rounded-full mr-2 
                                    {{ $subscription->status === 'active' ? 'bg-green-500' : 
                                       ($subscription->status === 'trialing' ? 'bg-yellow-500' : 
                                       'bg-red-500') }}"></span>
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">${{ $subscription->price }}</div>
                            <div class="text-sm text-gray-500">/month</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ ucfirst($subscription->billing_cycle) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($subscription->ends_at)
                                {{ $subscription->ends_at->format('M d, Y') }}
                            @else
                                <span class="text-gray-400">Never</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" 
                                   class="text-gray-600 hover:text-gray-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($subscription->status === 'active')
                                <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Cancel">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @elseif($subscription->status === 'cancelled')
                                <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Renew">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-credit-card text-4xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">No subscriptions found</p>
                            <p class="text-gray-500 text-sm mt-1">Start by adding your first subscription</p>
                            <a href="{{ route('admin.subscriptions.create') }}" 
                               class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Subscription
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subscriptions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscriptions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection