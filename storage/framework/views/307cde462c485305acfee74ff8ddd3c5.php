

<?php $__env->startSection('title', 'Dashboard - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Dashboard
            </h2>
            <p class="mt-1 text-sm text-gray-500">Welcome back, <?php echo e(auth()->user()->name); ?></p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
            <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </button>
            <button type="button" onclick="window.location.href='/company/<?php echo e(request()->route('tenant')); ?>/inventory/items/create'" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Item
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Items -->
        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 px-4 py-5 shadow sm:px-6">
            <dt>
                <div class="absolute rounded-md bg-white/20 p-3">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-white/80">Total Items</p>
            </dt>
            <dd class="ml-16 flex items-baseline">
                <p class="text-2xl font-semibold text-white"><?php echo e($stats['total_items'] ?? 1245); ?></p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-white/90">
                    <svg class="h-5 w-5 flex-shrink-0 self-center" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                    </svg>
                    5.2%
                </p>
            </dd>
        </div>

        <!-- Low Stock Items -->
        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-red-500 to-red-600 px-4 py-5 shadow sm:px-6">
            <dt>
                <div class="absolute rounded-md bg-white/20 p-3">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-white/80">Low Stock Alerts</p>
            </dt>
            <dd class="ml-16 flex items-baseline">
                <p class="text-2xl font-semibold text-white"><?php echo e($stats['low_stock'] ?? 23); ?></p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-white/90">
                    Needs attention
                </p>
            </dd>
        </div>

        <!-- Total Stock Value -->
        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-green-500 to-green-600 px-4 py-5 shadow sm:px-6">
            <dt>
                <div class="absolute rounded-md bg-white/20 p-3">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-white/80">Stock Value</p>
            </dt>
            <dd class="ml-16 flex items-baseline">
                <p class="text-2xl font-semibold text-white">$<?php echo e(number_format($stats['value'] ?? 125600)); ?></p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-white/90">
                    <svg class="h-5 w-5 flex-shrink-0 self-center" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                    </svg>
                    8.1%
                </p>
            </dd>
        </div>

        <!-- Warehouses -->
        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 px-4 py-5 shadow sm:px-6">
            <dt>
                <div class="absolute rounded-md bg-white/20 p-3">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-white/80">Active Warehouses</p>
            </dt>
            <dd class="ml-16 flex items-baseline">
                <p class="text-2xl font-semibold text-white"><?php echo e($warehouses ?? 5); ?></p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-white/90">
                    Locations
                </p>
            </dd>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <!-- Inventory Overview Chart -->
        <div class="lg:col-span-2 rounded-lg bg-white p-6 shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Inventory Overview</h3>
                <select class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <canvas id="inventoryChart" height="250"></canvas>
        </div>

        <!-- Stock Distribution -->
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Distribution</h3>
            <canvas id="stockPieChart" height="250"></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-blue-500 mr-2"></span>
                        <span class="text-sm text-gray-600">In Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">68%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Low Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">22%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-red-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Out of Stock</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Low Stock Items -->
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <!-- Recent Stock Movements -->
  <!-- Recent Stock Movements -->
<div class="rounded-lg bg-white shadow">
    <div class="px-6 py-5 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Stock Movements</h3>
    </div>
    <div class="divide-y divide-gray-200">
        <?php $__empty_1 = true; $__currentLoopData = $recentMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full <?php echo e($movement->type === 'in' ? 'bg-green-100' : 'bg-red-100'); ?> flex items-center justify-center">
                    <svg class="h-5 w-5 <?php echo e($movement->type === 'in' ? 'text-green-600' : 'text-red-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <?php if($movement->type === 'in'): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900"><?php echo e($movement->item->name ?? 'Unknown Item'); ?></p>
                    <p class="text-sm text-gray-500">
                        <?php echo e($movement->warehouse ? $movement->warehouse->name : 'Warehouse #' . $movement->warehouse_id); ?>

                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold <?php echo e($movement->type === 'in' ? 'text-green-600' : 'text-red-600'); ?>">
                    <?php echo e($movement->type === 'in' ? '+' : '-'); ?><?php echo e($movement->quantity); ?>

                </p>
                <p class="text-xs text-gray-500"><?php echo e($movement->created_at->diffForHumans()); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-6 py-8 text-center text-sm text-gray-500">
            No recent movements
        </div>
        <?php endif; ?>
    </div>
</div>

        <!-- Low Stock Alerts -->
        <div class="rounded-lg bg-white shadow">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Low Stock Alerts</h3>
                <a href="/company/<?php echo e(request()->route('tenant')); ?>/inventory/items?filter=low_stock" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $lowStockAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center flex-1">
                        <img class="h-10 w-10 rounded object-cover" src="<?php echo e($item->image ?? 'https://via.placeholder.com/40'); ?>" alt="">
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($item->name); ?></p>
                            <p class="text-sm text-gray-500">SKU: <?php echo e($item->sku); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-red-600"><?php echo e($item->quantity); ?> left</p>
                        <p class="text-xs text-gray-500">Min: <?php echo e($item->min_quantity); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-8 text-center text-sm text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2">All items are well stocked!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Inventory Overview Chart
const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
new Chart(inventoryCtx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Stock In',
            data: [150, 200, 180, 220, 250, 210, 190],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }, {
            label: 'Stock Out',
            data: [100, 120, 150, 130, 140, 160, 120],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Stock Distribution Pie Chart
const stockPieCtx = document.getElementById('stockPieChart').getContext('2d');
new Chart(stockPieCtx, {
    type: 'doughnut',
    data: {
        labels: ['In Stock', 'Low Stock', 'Out of Stock'],
        datasets: [{
            data: [68, 22, 10],
            backgroundColor: ['rgb(59, 130, 246)', 'rgb(234, 179, 8)', 'rgb(239, 68, 68)']
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/company/dashboard.blade.php ENDPATH**/ ?>