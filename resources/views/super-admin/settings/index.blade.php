@extends('layouts.super-admin')

@section('title', 'System Settings - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
                <p class="text-gray-600 mt-1">Configure platform settings and preferences</p>
            </div>
           <div class="mt-4 md:mt-0 flex space-x-3">
    <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="inline-flex items-center px-4 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
            <i class="fas fa-sync-alt mr-2"></i> Clear Cache
        </button>
    </form>
    
    <form action="{{ route('admin.settings.backup') }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-download mr-2"></i> Backup Database
        </button>
    </form>
</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg sticky top-6">
                <nav class="p-4">
                    <div class="space-y-1">
                        <button @click="activeTab = 'general'" 
                                :class="activeTab === 'general' ? 'bg-indigo-50 text-indigo-700 border-indigo-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full flex items-center justify-between px-4 py-3 border-l-4 border-transparent rounded-r-lg transition">
                            <div class="flex items-center">
                                <i class="fas fa-cog mr-3"></i>
                                <span class="font-medium">General Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                        
                        <button @click="activeTab = 'mail'" 
                                :class="activeTab === 'mail' ? 'bg-indigo-50 text-indigo-700 border-indigo-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full flex items-center justify-between px-4 py-3 border-l-4 border-transparent rounded-r-lg transition">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-3"></i>
                                <span class="font-medium">Mail Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                        
                        <button @click="activeTab = 'billing'" 
                                :class="activeTab === 'billing' ? 'bg-indigo-50 text-indigo-700 border-indigo-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full flex items-center justify-between px-4 py-3 border-l-4 border-transparent rounded-r-lg transition">
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-3"></i>
                                <span class="font-medium">Billing Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                        
                        <button @click="activeTab = 'security'" 
                                :class="activeTab === 'security' ? 'bg-indigo-50 text-indigo-700 border-indigo-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full flex items-center justify-between px-4 py-3 border-l-4 border-transparent rounded-r-lg transition">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt mr-3"></i>
                                <span class="font-medium">Security Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-2">
            <div x-data="settings()">
                <!-- General Settings -->
                <div x-show="activeTab === 'general'" class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="general">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                                <input type="text" name="app_name" value="{{ $settings['general']['app_name'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                                <input type="url" name="app_url" value="{{ $settings['general']['app_url'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                    <select name="timezone" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="UTC" {{ $settings['general']['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ $settings['general']['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="Europe/London" {{ $settings['general']['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="Asia/Kolkata" {{ $settings['general']['timezone'] == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                    <select name="currency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="USD" {{ $settings['general']['currency'] == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ $settings['general']['currency'] == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="GBP" {{ $settings['general']['currency'] == 'GBP' ? 'selected' : '' }}>GBP</option>
                                        <option value="INR" {{ $settings['general']['currency'] == 'INR' ? 'selected' : '' }}>INR</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                                    <input type="text" name="date_format" value="{{ $settings['general']['date_format'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                                    <input type="text" name="time_format" value="{{ $settings['general']['time_format'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                                    Save General Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Mail Settings -->
                <div x-show="activeTab === 'mail'" class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Mail Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="mail">
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mail Host</label>
                                    <input type="text" name="mail_host" value="{{ $settings['mail']['mail_host'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mail Port</label>
                                    <input type="number" name="mail_port" value="{{ $settings['mail']['mail_port'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Username</label>
                                <input type="text" name="mail_username" value="{{ $settings['mail']['mail_username'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Password</label>
                                <input type="password" name="mail_password" 
                                       placeholder="Enter new password to change"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mail Encryption</label>
                                    <select name="mail_encryption" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">None</option>
                                        <option value="tls" {{ $settings['mail']['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $settings['mail']['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mail From Address</label>
                                    <input type="email" name="mail_from_address" value="{{ $settings['mail']['mail_from_address'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail From Name</label>
                                <input type="text" name="mail_from_name" value="{{ $settings['mail']['mail_from_name'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="pt-6 border-t">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                                    Save Mail Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Billing Settings -->
                <div x-show="activeTab === 'billing'" class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Billing Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="billing">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                                <select name="currency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="USD" {{ $settings['billing']['currency'] == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ $settings['billing']['currency'] == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ $settings['billing']['currency'] == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" step="0.01" min="0" max="100" 
                                       value="{{ $settings['billing']['tax_rate'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Prefix</label>
                                <input type="text" name="invoice_prefix" value="{{ $settings['billing']['invoice_prefix'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Days</label>
                                    <input type="number" name="due_days" min="1" max="365"
                                           value="{{ $settings['billing']['due_days'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Late Fee (%)</label>
                                    <input type="number" name="late_fee" step="0.01" min="0"
                                           value="{{ $settings['billing']['late_fee'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                                    Save Billing Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div x-show="activeTab === 'security'" class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Security Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="security">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                                <input type="number" name="password_min_length" min="6" max="32"
                                       value="{{ $settings['security']['password_min_length'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="password_require_numbers" id="password_require_numbers" 
                                           {{ $settings['security']['password_require_numbers'] ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="password_require_numbers" class="ml-2 block text-sm text-gray-700">
                                        Require numbers in passwords
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="password_require_symbols" id="password_require_symbols"
                                           {{ $settings['security']['password_require_symbols'] ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="password_require_symbols" class="ml-2 block text-sm text-gray-700">
                                        Require symbols in passwords
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="password_require_mixed_case" id="password_require_mixed_case"
                                           {{ $settings['security']['password_require_mixed_case'] ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="password_require_mixed_case" class="ml-2 block text-sm text-gray-700">
                                        Require mixed case in passwords
                                    </label>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                    <input type="number" name="login_attempts" min="1" max="10"
                                           value="{{ $settings['security']['login_attempts'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lockout Time (minutes)</label>
                                    <input type="number" name="lockout_time" min="1" max="1440"
                                           value="{{ $settings['security']['lockout_time'] }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                                <input type="number" name="session_timeout" min="1" max="1440"
                                       value="{{ $settings['security']['session_timeout'] }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="pt-6 border-t">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                                    Save Security Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('settings', () => ({
        activeTab: 'general',
        
        init() {
            // Check URL hash for active tab
            const hash = window.location.hash.substring(1);
            if (hash && ['general', 'mail', 'billing', 'security'].includes(hash)) {
                this.activeTab = hash;
            }
        }
    }));
});
</script>
@endpush
@endsection