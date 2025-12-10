@extends('layouts.super-admin')

@section('title', $user->name . ' - User Details - SmartIMS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="text-gray-600">
                            <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Full Name</label>
                            <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email Address</label>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company</label>
                            <p class="mt-1 text-gray-900">{{ $user->tenant->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Role</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user->role === 'manager' ? 'bg-green-100 text-green-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Login</label>
                            <p class="mt-1 text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email Verified</label>
                            <p class="mt-1">
                                @if($user->email_verified_at)
                                <span class="inline-flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i> Yes
                                </span>
                                @else
                                <span class="inline-flex items-center text-red-600">
                                    <i class="fas fa-times-circle mr-2"></i> No
                                </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Account Created</label>
                            <p class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            @if($user->tenant)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company Name</label>
                            <p class="mt-1 text-gray-900">{{ $user->tenant->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Domain</label>
                            <p class="mt-1 text-gray-900">{{ $user->tenant->domain }}.smartims.test</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $user->tenant->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($user->tenant->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($user->tenant->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subscription Plan</label>
                            <p class="mt-1 text-gray-900">{{ $user->tenant->subscription->plan_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.companies.show', $user->tenant->id) }}" 
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-external-link-alt mr-2"></i> View Company Details
                        </a>
                    </div>
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
                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-between px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-sync-alt text-yellow-600 mr-3"></i>
                                <span class="text-sm font-medium text-yellow-800">
                                    {{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}
                                </span>
                            </div>
                            <i class="fas fa-chevron-right text-yellow-600"></i>
                        </button>
                    </form>
                    
                    <a href="mailto:{{ $user->email }}" 
                       class="flex items-center justify-between px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">Send Email</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    
                    <a href="#" 
                       class="flex items-center justify-between px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-key text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">Reset Password</span>
                        </div>
                        <i class="fas fa-chevron-right text-green-600"></i>
                    </a>
                    
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                class="w-full flex items-center justify-between px-4 py-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-trash text-red-600 mr-3"></i>
                                <span class="text-sm font-medium text-red-800">Delete User</span>
                            </div>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Login Activity -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Account Created</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($user->last_login_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Login</span>
                        <span class="text-sm text-gray-900">{{ $user->last_login_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Email Status</span>
                        <span class="text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection