@extends('layouts.super-admin')

@section('title', 'Edit Subscription - SmartIMS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Subscription</h1>
            <p class="text-gray-600 mt-1">Update subscription information</p>
        </div>

        <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Subscription Information -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Information</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                            <select name="plan_name" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="starter" {{ $subscription->plan_name == 'starter' ? 'selected' : '' }}>Starter</option>
                                <option value="growth" {{ $subscription->plan_name == 'growth' ? 'selected' : '' }}>Growth</option>
                                <option value="premium" {{ $subscription->plan_name == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="enterprise" {{ $subscription->plan_name == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                            <input type="number" name="price" required step="0.01" min="0" 
                                   value="{{ old('price', $subscription->price) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="trialing" {{ $subscription->status == 'trialing' ? 'selected' : '' }}>Trialing</option>
                                    <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Cycle *</label>
                                <select name="billing_cycle" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="monthly" {{ $subscription->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ $subscription->billing_cycle == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ $subscription->billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Trial Ends At</label>
                                <input type="date" name="trial_ends_at" 
                                       value="{{ old('trial_ends_at', $subscription->trial_ends_at ? $subscription->trial_ends_at->format('Y-m-d') : '') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Ends At</label>
                                <input type="date" name="ends_at"
                                       value="{{ old('ends_at', $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection