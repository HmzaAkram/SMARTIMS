@extends('layouts.app')

@section('title', 'Settings - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
            Company Settings
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your company settings and preferences</p>
    </div>

    <!-- Settings Form -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow">
        <form method="POST" action="{{ route('company.settings.update', $tenant) }}">
            @csrf
            @method('PUT')
            
            <!-- Company Information -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Company Information</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic information about your company</p>
                </div>
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">Company Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $settings->name) }}" required
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $settings->email) }}" required
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone) }}"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <textarea name="address" id="address" rows="2"
                                      class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">{{ old('address', $settings->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Settings -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Business Settings</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure your business preferences</p>
                </div>
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-900 dark:text-white">Currency *</label>
                            <select name="currency" id="currency" required
                                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="INR" {{ old('currency', 'INR') == 'INR' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                            </select>
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-900 dark:text-white">Timezone *</label>
                            <select name="timezone" id="timezone" required
                                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="Asia/Kolkata" {{ old('timezone', 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>India (IST)</option>
                                <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>London (GMT)</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_format" class="block text-sm font-medium text-gray-900 dark:text-white">Date Format *</label>
                            <select name="date_format" id="date_format" required
                                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="d/m/Y" {{ old('date_format', 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ old('date_format') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ old('date_format') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                <option value="d M Y" {{ old('date_format') == 'd M Y' ? 'selected' : '' }}>DD Mon YYYY</option>
                            </select>
                        </div>

                        <div>
                            <label for="time_format" class="block text-sm font-medium text-gray-900 dark:text-white">Time Format *</label>
                            <select name="time_format" id="time_format" required
                                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="h:i A" {{ old('time_format', 'h:i A') == 'h:i A' ? 'selected' : '' }}>12-hour (2:30 PM)</option>
                                <option value="H:i" {{ old('time_format') == 'H:i' ? 'selected' : '' }}>24-hour (14:30)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Settings -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Settings</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure inventory management</p>
                </div>
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-900 dark:text-white">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" 
                                   value="{{ old('low_stock_threshold', 10) }}" min="1"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Items below this quantity will be marked as low stock</p>
                        </div>

                        <div>
                            <label for="default_warehouse_id" class="block text-sm font-medium text-gray-900 dark:text-white">Default Warehouse</label>
                            <select name="default_warehouse_id" id="default_warehouse_id"
                                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('default_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="enable_barcode" id="enable_barcode" 
                                           {{ old('enable_barcode', true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    <label for="enable_barcode" class="ml-2 text-sm text-gray-900 dark:text-white">Enable Barcode Scanning</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="enable_sku" id="enable_sku" 
                                           {{ old('enable_sku', true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    <label for="enable_sku" class="ml-2 text-sm text-gray-900 dark:text-white">Enable SKU Generation</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Settings -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Settings</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure sales and invoicing</p>
                </div>
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-900 dark:text-white">Default Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                                   value="{{ old('tax_rate', 18) }}"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="invoice_prefix" class="block text-sm font-medium text-gray-900 dark:text-white">Invoice Prefix</label>
                            <input type="text" name="invoice_prefix" id="invoice_prefix" 
                                   value="{{ old('invoice_prefix', 'INV') }}"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="invoice_start_number" class="block text-sm font-medium text-gray-900 dark:text-white">Invoice Start Number</label>
                            <input type="number" name="invoice_start_number" id="invoice_start_number" min="1"
                                   value="{{ old('invoice_start_number', 1001) }}"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notification Settings</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure alerts and notifications</p>
                </div>
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="expiry_alert_days" class="block text-sm font-medium text-gray-900 dark:text-white">Expiry Alert Days</label>
                            <input type="number" name="expiry_alert_days" id="expiry_alert_days" min="1"
                                   value="{{ old('expiry_alert_days', 30) }}"
                                   class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alert before expiry (in days)</p>
                        </div>

                        <div class="sm:col-span-2">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="notify_low_stock" id="notify_low_stock" 
                                           {{ old('notify_low_stock', true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    <label for="notify_low_stock" class="ml-2 text-sm text-gray-900 dark:text-white">Notify on Low Stock</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="notify_expiry" id="notify_expiry" 
                                           {{ old('notify_expiry', true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    <label for="notify_expiry" class="ml-2 text-sm text-gray-900 dark:text-white">Notify before Expiry</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-5">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('company.dashboard', $tenant) }}" 
                       class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection