@extends('layouts.company')

@section('title', 'Company Settings - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Company Settings
        </h2>
        <p class="mt-1 text-sm text-gray-500">Manage your company profile and preferences</p>
    </div>

    <!-- Settings Tabs -->
    <div x-data="{ activeTab: 'general' }" class="space-y-6">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    General
                </button>
                <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Branding
                </button>
                <button @click="activeTab = 'users'" :class="activeTab === 'users' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Users & Roles
                </button>
                <button @click="activeTab = 'subscription'" :class="activeTab === 'subscription' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Subscription
                </button>
                <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Notifications
                </button>
            </nav>
        </div>

        <!-- General Settings -->
        <div x-show="activeTab === 'general'" x-transition class="space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Company Information</h3>
                </div>
                <form class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-900">Company Logo</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <img src="https://ui-avatars.com/api/?name=Company&size=80" alt="Logo" class="h-20 w-20 rounded-lg">
                                <button type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Change Logo
                                </button>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-gray-900">Company Name</label>
                            <input type="text" id="company_name" value="Acme Corp" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                            <input type="email" id="email" value="contact@acme.com" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-900">Phone</label>
                            <input type="tel" id="phone" value="+1 234 567 8900" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-900">Address</label>
                            <textarea id="address" rows="3" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">123 Business Street, City, Country</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Branding Settings -->
        <div x-show="activeTab === 'branding'" x-transition class="space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Brand Customization</h3>
                </div>
                <form class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-900">Primary Color</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <input type="color" id="primary_color" value="#4F46E5" class="h-10 w-20 rounded border-0 cursor-pointer">
                                <input type="text" value="#4F46E5" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-900">Secondary Color</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <input type="color" id="secondary_color" value="#7C3AED" class="h-10 w-20 rounded border-0 cursor-pointer">
                                <input type="text" value="#7C3AED" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Subdomain</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" value="acme" class="block w-full min-w-0 flex-1 rounded-l-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">.smartims.com</span>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Save Branding
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users & Roles -->
        <div x-show="activeTab === 'users'" x-transition class="space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                    <button type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Invite User
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @for($i = 1; $i <= 5; $i++)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=User+{{ $i }}" alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">User {{ $i }}</div>
                                            <div class="text-sm text-gray-500">user{{ $i }}@company.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-blue-100 text-blue-800">
                                        {{ $i === 1 ? 'Admin' : ($i === 2 ? 'Manager' : 'Staff') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Remove</button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Subscription -->
        <div x-show="activeTab === 'subscription'" x-transition class="space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Current Plan</h3>
                </div>
                <div class="p-6">
                    <div class="rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white">
                        <h4 class="text-2xl font-bold">Premium Plan</h4>
                        <p class="mt-2 text-white/80">$79/month • Billed annually</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold">$79</span>
                            <span class="ml-2 text-xl">/month</span>
                        </div>
                        <button class="mt-6 rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-gray-100">
                            Manage Subscription
                        </button>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Plan Features</h4>
                        <ul class="space-y-3">
                            @foreach(['Unlimited items', '10 warehouses', 'Advanced analytics', 'Priority support', 'Custom branding'] as $feature)
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div x-show="activeTab === 'notifications'" x-transition class="space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Notification Preferences</h3>
                </div>
                <div class="p-6 space-y-6">
                    @foreach(['Low stock alerts', 'New orders', 'Stock transfers', 'System updates', 'Weekly reports'] as $notif)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $notif }}</p>
                            <p class="text-sm text-gray-500">Receive notifications about {{ strtolower($notif) }}</p>
                        </div>
                        <button type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                            <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    @endforeach

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Save Preferences
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Inventory Overview Chart
const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
new Chart(inventoryCtx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Stock In',
            data: @json($stockInData),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Stock Out',
            data: @json($stockOutData),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Stock Distribution Pie Chart
const stockPieCtx = document.getElementById('stockPieChart').getContext('2d');
new Chart(stockPieCtx, {
    type: 'doughnut',
    data: {
        labels: @json($categories),
        datasets: [{
            data: @json($categoryDistribution),
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(234, 179, 8)',
                'rgb(239, 68, 68)',
                'rgb(168, 85, 247)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection
