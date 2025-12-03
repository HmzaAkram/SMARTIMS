<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartIMS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    }" class="min-h-screen">
        
        <!-- Sidebar -->
        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg transform transition-transform duration-300 lg:translate-x-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
        >
            <!-- Merged Navigation Content -->
            <!-- Logo & Company Info -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">SmartIMS</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(request()->route('tenant')) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Search (Added for user-friendliness) -->
            <div class="px-6 py-4">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Search menu..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 text-sm text-gray-900 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('company.dashboard', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Inventory Section -->
                <div x-data="{ open: {{ request()->routeIs('company.items.*') || request()->routeIs('company.categories.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.items.*') || request()->routeIs('company.categories.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-expanded="true" aria-controls="inventory-submenu">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Inventory</span>
                        </div>
                        <svg 
                            class="w-4 h-4 transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse id="inventory-submenu" class="ml-4 space-y-1 border-l-2 border-gray-200 dark:border-gray-700">
                        <a href="{{ route('company.items.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.items.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="All Items">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>All Items</span>
                        </a>
                        <a href="{{ route('company.categories.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.categories.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Categories">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Categories</span>
                        </a>
                    </div>
                </div>

                <!-- Warehouses -->
                <a href="{{ route('company.warehouses.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.warehouses.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Warehouses">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Warehouses</span>
                </a>

                <!-- Stock Movements -->
                <a href="{{ route('company.stock-movements.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.stock-movements.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Stock Movements">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Stock Movements</span>
                </a>

                <!-- Divider -->
                <div class="py-2">
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                </div>

                <!-- Sales & Orders Section -->
                <div x-data="{ open: {{ request()->routeIs('company.orders.*') || request()->routeIs('company.sales.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.orders.*') || request()->routeIs('company.sales.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-expanded="true" aria-controls="sales-submenu">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Sales & Orders</span>
                        </div>
                        <svg 
                            class="w-4 h-4 transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse id="sales-submenu" class="ml-4 space-y-1 border-l-2 border-gray-200 dark:border-gray-700">
                        <a href="{{ route('company.orders.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.orders.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Orders">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Orders</span>
                        </a>
                        <a href="{{ route('company.sales.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.sales.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Sales Reports">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Sales Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Suppliers -->
                <a href="{{ route('company.suppliers.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.suppliers.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Suppliers">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Suppliers</span>
                </a>

                <!-- Customers -->
                <a href="{{ route('company.customers.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.customers.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Customers">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Customers</span>
                </a>

                <!-- Divider -->
                <div class="py-2">
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                </div>

                <!-- Reports Section -->
                <div x-data="{ open: {{ request()->routeIs('company.reports.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.reports.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-expanded="true" aria-controls="reports-submenu">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Reports</span>
                        </div>
                        <svg 
                            class="w-4 h-4 transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse id="reports-submenu" class="ml-4 space-y-1 border-l-2 border-gray-200 dark:border-gray-700">
                        <a href="{{ route('company.reports.inventory', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.reports.inventory') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Inventory Report">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/>
                            </svg>
                            <span>Inventory Report</span>
                        </a>
                        <a href="{{ route('company.reports.sales', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.reports.sales') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Sales Report">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>Sales Report</span>
                        </a>
                        <a href="{{ route('company.reports.stock-movements', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.reports.stock-movements') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}" aria-label="Movement Report">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                            <span>Movement Report</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="{{ route('company.settings', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all hover:shadow-sm {{ request()->routeIs('company.settings') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" aria-label="Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Quick Actions -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-4 border border-blue-100 dark:border-blue-800">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Quick Actions</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Streamline your workflow</p>
                            <div class="space-y-2">
                                <a href="{{ route('company.items.create', ['tenant' => request()->route('tenant')]) }}" class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add New Item
                                </a>
                                <a href="{{ route('company.stock-movements.create', ['tenant' => request()->route('tenant')]) }}" class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Record Movement
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Info (Optional) -->
            <div class="px-4 pb-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Storage Usage</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">68%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full" style="width: 68%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">6.8 GB of 10 GB used</p>
                </div>
            </div>
            <!-- End of Merged Navigation Content -->

        </aside>

        <!-- Mobile sidebar backdrop -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        ></div>

        <!-- Main Content -->
        <div 
            class="transition-all duration-300"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
        >
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left: Menu Toggle & Breadcrumb -->
                        <div class="flex items-center gap-4">
                            <button 
                                @click="toggleSidebar()"
                                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <!-- Breadcrumb -->
                            <nav class="hidden md:flex items-center space-x-2 text-sm">
                                <a href="{{ route('company.dashboard', ['tenant' => request()->route('tenant')]) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Dashboard
                                </a>
                                @yield('breadcrumb')
                            </nav>
                        </div>

                        <!-- Right: Search, Notifications, Theme Toggle, Profile -->
                        <div class="flex items-center gap-3">
                            <!-- Search -->
                            <div class="hidden lg:block" x-data="{ searchOpen: false }">
                                <button 
                                    @click="searchOpen = true"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span>Search...</span>
                                    <kbd class="px-2 py-0.5 text-xs bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded">⌘K</kbd>
                                </button>
                            </div>

                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button 
                                    @click="open = !open"
                                    class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>

                                <!-- Notifications Dropdown -->
                                <div 
                                    x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
                                    style="display: none;"
                                >
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Low stock alert</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">5 items below minimum quantity</p>
                                        </a>
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New order received</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Order #12345 needs processing</p>
                                        </a>
                                    </div>
                                    <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                                        <a href="#" class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                            View all notifications
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme Toggle -->
                            <button 
                                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button 
                                    @click="open = !open"
                                    class="flex items-center gap-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                >
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(request()->route('tenant')) }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Profile Dropdown -->
                                <div 
                                    x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
                                    style="display: none;"
                                >
                                    <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Profile Settings
                                        </a>
                                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Company Settings
                                        </a>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mt-auto py-6 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} SmartIMS. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Documentation</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Support</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Terms</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>