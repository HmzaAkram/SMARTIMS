@extends('layouts.super-admin')

@section('title', 'Create Company - SmartIMS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create New Company</h1>
            <p class="text-gray-600 mt-1">Add a new company to the platform</p>
        </div>

        <form action="{{ route('admin.companies.store') }}" method="POST">
            @csrf
            
            <!-- Company Information -->
            <div class="space-y-6">
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" name="name" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter company name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Email *</label>
                            <input type="email" name="email" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="company@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Domain *</label>
                            <div class="flex">
                                <input type="text" name="domain" required
                                       class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="company">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-600">
                                    .smartims.test
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Only letters and numbers, no spaces or special characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plan *</label>
                            <select name="plan" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Plan</option>
                                <option value="starter">Starter - $230/month</option>
                                <option value="growth">Growth - $450/month</option>
                                <option value="premium">Premium - $750/month</option>
                                <option value="enterprise">Enterprise - $1200/month</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active">Active</option>
                                <option value="trialing">Trialing (14 days)</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Admin Account -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Admin Account</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Name *</label>
                            <input type="text" name="admin_name" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="John Doe">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email *</label>
                            <input type="email" name="admin_email" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="admin@example.com">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                            <input type="password" name="admin_password" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter password">
                            <p class="mt-2 text-sm text-gray-500">Minimum 8 characters with letters, numbers, and symbols</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.companies.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    Create Company
                </button>
            </div>
        </form>
    </div>
</div>
@endsection