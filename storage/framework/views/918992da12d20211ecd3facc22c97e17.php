<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" 
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
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'SmartIMS')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        .sidebar-scrollbar {
            scrollbar-width: thin;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 10px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
        
        .dark .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }
        
        .dark .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
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
        
        /* Modern hover effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .dark .hover-lift:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Active menu indicator */
        .active-indicator {
            position: relative;
        }
        
        .active-indicator::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        
        /* Pulse animation for notifications */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
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

        <!-- Enhanced Modern Sidebar -->
        <aside x-show="sidebarOpen"
               x-transition:enter="transition-transform duration-300 ease-out"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-300 ease-in"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 lg:static lg:z-0 flex flex-col shadow-xl"
               :class="darkMode ? 'bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900' : 'bg-gradient-to-b from-white via-gray-50 to-gray-100'"
               x-cloak>
            
            <!-- Logo & Company Info -->
            <div class="h-20 flex flex-col justify-center px-6 border-b" 
                 :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg glow-effect"
                         :class="darkMode ? 'bg-gradient-to-br from-blue-600 to-indigo-600' : 'bg-gradient-to-br from-blue-500 to-indigo-600'">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">SmartIMS</h1>
                        <p class="text-xs mt-0.5" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"><?php echo e(ucfirst(request()->route('tenant'))); ?></p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Search with Modern Design -->
            <div class="p-5 border-b" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4.5 w-4.5 transition-colors duration-300" 
                             :class="darkMode ? 'text-gray-400 group-focus-within:text-blue-400' : 'text-gray-500 group-focus-within:text-blue-500'" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           class="w-full pl-11 pr-4 py-3 text-sm rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-1"
                           :class="darkMode ? 
                                   'bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500 focus:ring-blue-500 focus:border-transparent' : 
                                   'bg-white/80 border-gray-300 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-transparent border shadow-sm hover:border-gray-400'"
                           placeholder="Search menu...">
                </div>
            </div>

            <!-- Navigation - Modern Compact Design -->
            <nav class="flex-1 overflow-y-auto sidebar-scrollbar px-4 py-5 space-y-1">
                <!-- Dashboard -->
                <a href="<?php echo e(route('company.dashboard', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group relative overflow-hidden hover-lift"
                   :class="request()->routeIs('company.dashboard') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.dashboard') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="flex-1">Dashboard</span>
                    <span x-show="request()->routeIs('company.dashboard')" 
                          class="w-2 h-2 rounded-full animate-pulse"
                          :class="darkMode ? 'bg-blue-400' : 'bg-blue-500'"></span>
                </a>

                <!-- Inventory with Modern Dropdown -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.items.*') || request()->routeIs('company.categories.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                            :class="darkMode ? 
                                    'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 
                                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80'">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                                 :class="darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700'" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Inventory</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" 
                             class="w-4 h-4 transition-all duration-300 flex-shrink-0"
                             :class="darkMode ? 'text-gray-500' : 'text-gray-500'" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 space-y-1"
                         :class="darkMode ? 'border-blue-800/50' : 'border-blue-200'">
                        <a href="<?php echo e(route('company.items.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.items.*') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>All Items</span>
                        </a>
                        <a href="<?php echo e(route('company.categories.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.categories.*') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Categories</span>
                        </a>
                    </div>
                </div>

                <!-- Warehouses -->
                <a href="<?php echo e(route('company.warehouses.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                   :class="request()->routeIs('company.warehouses.*') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.warehouses.*') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="flex-1">Warehouses</span>
                </a>

                <!-- Stock Movements -->
                <a href="<?php echo e(route('company.stock-movements.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                   :class="request()->routeIs('company.stock-movements.*') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.stock-movements.*') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="flex-1">Stock Movements</span>
                </a>

                <!-- Divider with Modern Style -->
                <div class="pt-4 pb-2">
                    <div class="h-px" :class="darkMode ? 'bg-gradient-to-r from-transparent via-gray-700 to-transparent' : 'bg-gradient-to-r from-transparent via-gray-300 to-transparent'"></div>
                </div>

                <!-- Sales & Orders Dropdown -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.orders.*') || request()->routeIs('company.sales.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                            :class="darkMode ? 
                                    'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 
                                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80'">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                                 :class="darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700'" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Sales & Orders</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" 
                             class="w-4 h-4 transition-all duration-300 flex-shrink-0"
                             :class="darkMode ? 'text-gray-500' : 'text-gray-500'" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 space-y-1"
                         :class="darkMode ? 'border-blue-800/50' : 'border-blue-200'">
                        <a href="<?php echo e(route('company.orders.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.orders.*') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Orders</span>
                        </a>
                        <a href="<?php echo e(route('company.sales.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.sales.*') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Sales Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Suppliers -->
                <a href="<?php echo e(route('company.suppliers.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                   :class="request()->routeIs('company.suppliers.*') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.suppliers.*') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="flex-1">Suppliers</span>
                </a>

                <!-- Customers -->
                <a href="<?php echo e(route('company.customers.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                   :class="request()->routeIs('company.customers.*') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.customers.*') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1">Customers</span>
                </a>

                <!-- Divider -->
                <div class="pt-4 pb-2">
                    <div class="h-px" :class="darkMode ? 'bg-gradient-to-r from-transparent via-gray-700 to-transparent' : 'bg-gradient-to-r from-transparent via-gray-300 to-transparent'"></div>
                </div>

                <!-- Reports -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.reports.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                            :class="darkMode ? 
                                    'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 
                                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80'">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                                 :class="darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700'" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Reports</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" 
                             class="w-4 h-4 transition-all duration-300 flex-shrink-0"
                             :class="darkMode ? 'text-gray-500' : 'text-gray-500'" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 space-y-1"
                         :class="darkMode ? 'border-blue-800/50' : 'border-blue-200'">
                        <a href="<?php echo e(route('company.reports.inventory', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.reports.inventory') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <span>Inventory Report</span>
                        </a>
                        <a href="<?php echo e(route('company.reports.sales', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-300 group"
                           :class="request()->routeIs('company.reports.sales') ? 
                                   (darkMode ? 'text-blue-400 bg-blue-900/20' : 'text-blue-700 bg-blue-50') : 
                                   (darkMode ? 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/30' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100')">
                            <span>Sales Report</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="<?php echo e(route('company.settings', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-300 group hover-lift"
                   :class="request()->routeIs('company.settings') ? 
                           (darkMode ? 'bg-blue-900/30 text-blue-400 border border-blue-800/50 active-indicator' : 'bg-blue-50 text-blue-700 border border-blue-200 active-indicator') : 
                           (darkMode ? 'text-gray-400 hover:text-gray-300 hover:bg-gray-800/50' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100/80')">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-300" 
                         :class="request()->routeIs('company.settings') ? 
                                 (darkMode ? 'text-blue-400' : 'text-blue-600') : 
                                 (darkMode ? 'text-gray-500 group-hover:text-gray-300' : 'text-gray-500 group-hover:text-gray-700')" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1">Settings</span>
                </a>

                <!-- Quick Actions Modern Card -->
                <div class="mt-8 mx-4">
                    <div class="rounded-xl p-4 border transition-all duration-300 hover:shadow-lg glow-effect"
                         :class="darkMode ? 
                                 'bg-gradient-to-br from-gray-800 to-gray-900 border-gray-700' : 
                                 'bg-gradient-to-br from-blue-50 to-white border-blue-100 shadow-sm'">
                        <h3 class="text-sm font-semibold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">Quick Actions</h3>
                        <div class="space-y-2.5">
                            <a href="<?php echo e(route('company.items.create', ['tenant' => request()->route('tenant')])); ?>" 
                               class="flex items-center justify-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-sm"
                               :class="darkMode ? 
                                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800' : 
                                       'bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700'">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New Item
                            </a>
                            <a href="<?php echo e(route('company.stock-movements.create', ['tenant' => request()->route('tenant')])); ?>" 
                               class="flex items-center justify-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] border"
                               :class="darkMode ? 
                                       'border-gray-700 text-gray-300 hover:bg-gray-800' : 
                                       'border-blue-200 text-blue-700 hover:bg-blue-50'">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                                Record Movement
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Card Modern Design -->
                <div class="mt-4 mx-4">
                    <div class="rounded-xl p-4 border transition-all duration-300 hover-lift"
                         :class="darkMode ? 
                                 'bg-gradient-to-br from-gray-800 to-gray-900 border-gray-700' : 
                                 'bg-gradient-to-br from-indigo-50 to-white border-indigo-100'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium" :class="darkMode ? 'text-gray-400' : 'text-indigo-600'">Stock Value</span>
                            <span class="text-xs px-2 py-1 rounded-full" 
                                  :class="darkMode ? 'bg-green-900/30 text-green-400' : 'bg-green-100 text-green-700'">
                                +8.1%
                            </span>
                        </div>
                        <div class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">$360,000</div>
                        <div class="mt-3 h-2 rounded-full overflow-hidden" :class="darkMode ? 'bg-gray-700' : 'bg-gray-200'">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-500 to-emerald-500" style="width: 81%"></div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- User Profile Modern Design -->
            <div class="mt-auto p-4 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group cursor-pointer hover-lift"
                     :class="darkMode ? 'hover:bg-gray-800/50' : 'hover:bg-gray-100'">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg"
                             :class="darkMode ? 
                                     'bg-gradient-to-br from-blue-600 to-indigo-600' : 
                                     'bg-gradient-to-br from-blue-500 to-indigo-600'">
                            <span class="text-sm font-semibold text-white"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2"
                             :class="darkMode ? 'bg-green-500 border-gray-800' : 'bg-green-500 border-white'"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" :class="darkMode ? 'text-white' : 'text-gray-900'"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs truncate" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="p-2 rounded-lg transition-all duration-300 hover:scale-110"
                                :class="darkMode ? 
                                        'text-gray-400 hover:text-red-400 hover:bg-red-900/20' : 
                                        'text-gray-500 hover:text-red-600 hover:bg-red-50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" 
                    class="lg:hidden absolute top-5 right-4 p-1.5 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-all-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
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
                                <h1 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back, <?php echo e(auth()->user()->name); ?></p>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-3">
                            <!-- Theme Toggle -->
                            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all-300 hover-lift">
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
                                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all-300 hover-lift">
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
                <?php if(session('success')): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center justify-between hover-lift">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-800 dark:text-green-200"><?php echo e(session('success')); ?></p>
                        </div>
                        <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 transition-all-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center justify-between hover-lift">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-800 dark:text-red-200"><?php echo e(session('error')); ?></p>
                        </div>
                        <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 transition-all-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <footer class="py-4 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; <?php echo e(date('Y')); ?> SmartIMS. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Help</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Privacy</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-200 transition-all-300">Terms</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/layouts/company.blade.php ENDPATH**/ ?>