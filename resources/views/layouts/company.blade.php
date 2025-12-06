<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarOpen: window.innerWidth >= 1024,
          activeDropdown: null
      }" 
      x-bind:class="{ 'dark': darkMode }"
      x-init="
          // Handle window resize
          window.addEventListener('resize', () => {
              if (window.innerWidth >= 1024) {
                  sidebarOpen = true;
              } else {
                  sidebarOpen = false;
              }
          });
          
          // Close dropdowns when clicking outside
          document.addEventListener('click', (e) => {
              if (!e.target.closest('[x-data]')) {
                  activeDropdown = null;
              }
          });
      ">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartIMS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 2px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        
        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        /* Glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
        
        .dark .glass-effect {
            background: rgba(31, 41, 55, 0.7);
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition.opacity.duration.300ms
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
             x-cloak>
        </div>

        <!-- Sidebar -->
        <aside x-show="sidebarOpen"
               x-transition:enter="transition-transform duration-300 ease-out"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-300 ease-in"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 lg:static lg:z-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-xl flex flex-col"
               x-cloak>
            
            <!-- Logo & Company Info -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">SmartIMS</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(request()->route('tenant')) }}</p>
                    </div>
                </div>
                <!-- Close button for mobile -->
                <button @click="sidebarOpen = false" 
                        class="lg:hidden p-1.5 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-all-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Search -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                           placeholder="Search menu...">
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scrollbar px-4 py-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('company.dashboard', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.dashboard') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Inventory -->
                <div x-data="{ open: {{ request()->routeIs('company.items.*') || request()->routeIs('company.categories.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Inventory</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 border-blue-200 dark:border-blue-800 space-y-1">
                        <a href="{{ route('company.items.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.items.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>All Items</span>
                        </a>
                        <a href="{{ route('company.categories.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.categories.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Categories</span>
                        </a>
                    </div>
                </div>

                <!-- Warehouses -->
                <a href="{{ route('company.warehouses.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.warehouses.*') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Warehouses</span>
                </a>

                <!-- Stock Movements -->
                <a href="{{ route('company.stock-movements.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.stock-movements.*') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Stock Movements</span>
                </a>

                <div class="pt-4 pb-2">
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                </div>

                <!-- Sales & Orders -->
                <div x-data="{ open: {{ request()->routeIs('company.orders.*') || request()->routeIs('company.sales.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Sales & Orders</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 border-blue-200 dark:border-blue-800 space-y-1">
                        <a href="{{ route('company.orders.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.orders.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Orders</span>
                        </a>
                        <a href="{{ route('company.sales.index', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.sales.*') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Sales Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Suppliers -->
                <a href="{{ route('company.suppliers.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.suppliers.*') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Suppliers</span>
                </a>

                <!-- Customers -->
                <a href="{{ route('company.customers.index', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.customers.*') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Customers</span>
                </a>

                <div class="pt-4 pb-2">
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                </div>

                <!-- Reports -->
                <div x-data="{ open: {{ request()->routeIs('company.reports.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Reports</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 border-blue-200 dark:border-blue-800 space-y-1">
                        <a href="{{ route('company.reports.inventory', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.reports.inventory') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <span>Inventory Report</span>
                        </a>
                        <a href="{{ route('company.reports.sales', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all-300 {{ request()->routeIs('company.reports.sales') ? 'text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                            <span>Sales Report</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="{{ route('company.settings', ['tenant' => request()->route('tenant')]) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all-300 {{ request()->routeIs('company.settings') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Quick Actions -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('company.items.create', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-all-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Item
                        </a>
                        <a href="{{ route('company.stock-movements.create', ['tenant' => request()->route('tenant')]) }}" 
                           class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-all-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Record Movement
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile & Logout -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition-all-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden"
             :class="{ 'lg:ml-64': sidebarOpen }">
            <!-- Top Navigation -->
            <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm glass-effect">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left: Menu Toggle -->
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 lg:hidden transition-all-300">
                                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="ml-4 lg:ml-0">
                                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('title', 'Dashboard')</h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-3">
                            <!-- Theme Toggle -->
                            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all-300">
                                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>

                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                </button>
                                <div x-show="open" @click.outside="open = false" 
                                     x-transition
                                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                                     x-cloak>
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <!-- Notification items -->
                                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all-300">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New order received</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Order #12345 needs processing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 transition-all-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 transition-all-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-4 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} SmartIMS. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Help</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Privacy</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Terms</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>