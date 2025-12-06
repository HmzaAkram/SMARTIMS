

<?php $__env->startSection('title', 'Dashboard - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Dashboard Overview</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Welcome back, <?php echo e(auth()->user()->name); ?></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </button>
            <a href="/company/<?php echo e(request()->route('tenant')); ?>/inventory/items/create" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                </svg>
                Add Item
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Items -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Items</p>
                    <p class="text-2xl font-bold mt-2"><?php echo e($stats['total_items'] ?? 1245); ?></p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/>
                </svg>
                <span>5.2% from last month</span>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Low Stock Alerts</p>
                    <p class="text-2xl font-bold mt-2"><?php echo e($stats['low_stock'] ?? 23); ?></p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-red-100">
                Needs attention
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100">Stock Value</p>
                    <p class="text-2xl font-bold mt-2">$<?php echo e(number_format($stats['value'] ?? 125600)); ?></p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/>
                </svg>
                <span>8.1% from last month</span>
            </div>
        </div>

        <!-- Warehouses -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Active Warehouses</p>
                    <p class="text-2xl font-bold mt-2"><?php echo e($warehouses ?? 5); ?></p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                Active locations
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Inventory Overview Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Overview</h3>
                <select class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Stock Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Stock Distribution</h3>
            <div class="h-48 mb-6">
                <canvas id="stockPieChart"></canvas>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">In Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">68%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Low Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">22%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Out of Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Low Stock Items -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Stock Movements -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Stock Movements</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $recentMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-10 rounded-full <?php echo e($movement->type === 'in' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30'); ?> flex items-center justify-center">
                            <svg class="h-5 w-5 <?php echo e($movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php if($movement->type === 'in'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                <?php else: ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                <?php endif; ?>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($movement->item->name ?? 'Unknown Item'); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?php echo e($movement->warehouse ? $movement->warehouse->name : 'Warehouse #' . $movement->warehouse_id); ?>

                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold <?php echo e($movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                            <?php echo e($movement->type === 'in' ? '+' : '-'); ?><?php echo e($movement->quantity); ?>

                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($movement->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No recent movements</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Low Stock Alerts</h3>
                <a href="/company/<?php echo e(request()->route('tenant')); ?>/inventory/items?filter=low_stock" 
                   class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                    View all
                </a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $lowStockAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <img class="h-10 w-10 rounded-lg object-cover" 
                             src="<?php echo e($item->image ?? 'https://images.unsplash.com/photo-1580910654019-3bd4c8e4e8b4?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80'); ?>" 
                             alt="<?php echo e($item->name); ?>">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[150px]"><?php echo e($item->name); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SKU: <?php echo e($item->sku); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-red-600 dark:text-red-400"><?php echo e($item->quantity); ?> left</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Min: <?php echo e($item->min_quantity); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">All items are well stocked!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Wait for Alpine.js to be ready
document.addEventListener('alpine:init', () => {
    // Initialize charts when they are visible
    setTimeout(() => {
        // Inventory Overview Chart
        const inventoryCtx = document.getElementById('inventoryChart');
        if (inventoryCtx) {
            new Chart(inventoryCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Stock In',
                        data: [150, 200, 180, 220, 250, 210, 190],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Stock Out',
                        data: [100, 120, 150, 130, 140, 160, 120],
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
                            position: 'bottom',
                            labels: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#fff' : '#374151'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280'
                            }
                        },
                        x: {
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280'
                            }
                        }
                    }
                }
            });
        }

        // Stock Distribution Pie Chart
        const stockPieCtx = document.getElementById('stockPieChart');
        if (stockPieCtx) {
            new Chart(stockPieCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        data: [68, 22, 10],
                        backgroundColor: ['rgb(59, 130, 246)', 'rgb(234, 179, 8)', 'rgb(239, 68, 68)'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }, 100);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/company/dashboard.blade.php ENDPATH**/ ?>