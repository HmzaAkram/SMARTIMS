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
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb; /* lighter thumb */
            border-radius: 2px;
        }
        
        .dark .sidebar-scrollbar::-webkit-scrollbar-thumb {
             background: #374151;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
        
        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        /* Glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
        }
        
        .dark .glass-effect {
            background: rgba(17, 24, 39, 0.9);
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex">
        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition.opacity.duration.300ms
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-900/20 lg:hidden"
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
               class="fixed inset-y-0 left-0 z-50 w-64 lg:static lg:z-0 bg-white dark:bg-[#0B1120] border-r border-gray-100 dark:border-gray-800 flex flex-col"
               x-cloak>
            
            <!-- Logo & Company Info -->
            <div class="h-14 flex items-center px-5 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3 w-full">
                    <div class="w-6 h-6 bg-indigo-600 rounded-md flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-sm font-semibold text-gray-900 dark:text-white tracking-tight">SmartIMS</h1>
                    </div>
                </div>
                <!-- Close button for mobile -->
                <button @click="sidebarOpen = false" 
                        class="lg:hidden p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scrollbar px-3 py-6 space-y-0.5">
                
                <!-- Dashboard -->
                <a href="<?php echo e(route('company.dashboard', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.dashboard') ? 'bg-gray-100 text-gray-900 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.dashboard') ? 'text-gray-900 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="mt-6 mb-2 px-3 text-[11px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Inventory</div>

                <!-- Active Group Logic for Inventory -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.items.*') || request()->routeIs('company.categories.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 text-[13px] font-medium rounded-md transition-colors hover:bg-gray-50 dark:hover:bg-white/5 group text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Products</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 space-y-0.5">
                        <a href="<?php echo e(route('company.items.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.items.*') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            All Products
                        </a>
                        <a href="<?php echo e(route('company.categories.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.categories.*') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            Categories
                        </a>
                    </div>
                </div>

                <!-- Warehouses -->
                <a href="<?php echo e(route('company.warehouses.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.warehouses.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.warehouses.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Warehouses</span>
                </a>

                <!-- Stock Movements -->
                <a href="<?php echo e(route('company.stock-movements.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.stock-movements.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.stock-movements.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Stock Movements</span>
                </a>

                <div class="mt-6 mb-2 px-3 text-[11px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Commercial</div>

                <!-- Sales & Orders -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.orders.*') || request()->routeIs('company.sales.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 text-[13px] font-medium rounded-md transition-colors hover:bg-gray-50 dark:hover:bg-white/5 group text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Sales & Orders</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 space-y-0.5">
                        <a href="<?php echo e(route('company.orders.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.orders.*') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            All Orders
                        </a>
                        <a href="<?php echo e(route('company.sales.index', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.sales.*') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            Sales Reports
                        </a>
                    </div>
                </div>

                <!-- Customers -->
                <a href="<?php echo e(route('company.customers.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.customers.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.customers.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Customers</span>
                </a>

                 <!-- Suppliers -->
                 <a href="<?php echo e(route('company.suppliers.index', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.suppliers.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.suppliers.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Suppliers</span>
                </a>

                <div class="mt-6 px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">System</div>

                 <!-- Reports -->
                <div x-data="{ open: <?php echo e(request()->routeIs('company.reports.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 text-[13px] font-medium rounded-md transition-colors hover:bg-gray-50 dark:hover:bg-white/5 group text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Reports</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 space-y-0.5">
                        <a href="<?php echo e(route('company.reports.inventory', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.reports.inventory') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            Inventory
                        </a>
                        <a href="<?php echo e(route('company.reports.sales', ['tenant' => request()->route('tenant')])); ?>" 
                           class="flex items-center gap-3 pl-10 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.reports.sales') ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-500/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'); ?>">
                            Sales
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="<?php echo e(route('company.settings', ['tenant' => request()->route('tenant')])); ?>" 
                   class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-md transition-colors <?php echo e(request()->routeIs('company.settings') ? 'bg-indigo-50 text-indigo-600 dark:bg-white/5 dark:text-white' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5'); ?>">
                    <svg class="w-4 h-4 <?php echo e(request()->routeIs('company.settings') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Profile & Logout (Minimal) -->
            <div class="p-3 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 font-semibold text-xs">
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-gray-900 dark:text-white truncate"><?php echo e(Auth::user()->name); ?></p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none lg:hidden">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Theme Toggle -->
                            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <template x-if="!darkMode">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </template>
                                <template x-if="darkMode">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </template>
                            </button>

                            <!-- Notifications -->
                            <button class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white dark:ring-gray-800"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <footer class="py-4 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; <?php echo e(date('Y')); ?> SmartIMS. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/layouts/company.blade.php ENDPATH**/ ?>