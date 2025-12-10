@extends('layouts.super-admin')

@section('title', 'Edit ' . $company->name . ' - SmartIMS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Company: {{ $company->name }}</h1>
            <p class="text-gray-600 mt-1">Update company information and settings</p>
        </div>

        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Company Information -->
            <div class="space-y-6">
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" name="name" required value="{{ old('name', $company->name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter company name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Email *</label>
                            <input type="email" name="email" required value="{{ old('email', $company->email) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="company@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Domain *</label>
                            <div class="flex">
                                <input type="text" name="domain" required value="{{ old('domain', $company->domain) }}"
                                       class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="company">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-600">
                                    .smartims.test
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ $company->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trialing" {{ $company->status == 'trialing' ? 'selected' : '' }}>Trialing</option>
                                <option value="suspended" {{ $company->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        @if($company->subscription)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plan *</label>
                            <select name="plan" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="starter" {{ $company->subscription->plan_name == 'starter' ? 'selected' : '' }}>Starter</option>
                                <option value="growth" {{ $company->subscription->plan_name == 'growth' ? 'selected' : '' }}>Growth</option>
                                <option value="premium" {{ $company->subscription->plan_name == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="enterprise" {{ $company->subscription->plan_name == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.companies.show', $company->id) }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    Update Company
                </button>
            </div>
        </form>
    </div>
</div>
@endsection