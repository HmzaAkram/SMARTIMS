

<?php $__env->startSection('title', 'Warehouses - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Warehouses
            </h2>
            <p class="mt-1 text-sm text-gray-500">Manage your warehouse locations and inventory distribution</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('company.warehouses.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Warehouse
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Warehouses</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($warehouses->count()); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Capacity</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e(number_format($warehouses->sum('storage_capacity'))); ?> <span class="text-base">units</span></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Items Stored</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e(number_format($warehouses->sum('current_stock'))); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Avg. Utilization</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e(round($warehouses->avg(function ($warehouse) { return $warehouse->storage_capacity > 0 ? ($warehouse->current_stock / $warehouse->storage_capacity * 100) : 0; }))); ?>%</dd>
        </div>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php $__empty_1 = true; $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rounded-lg bg-white shadow hover:shadow-lg transition-shadow">
            <!-- Warehouse Image/Icon -->
            <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-t-lg relative overflow-hidden">
                <?php if($warehouse->image): ?>
                    <img src="<?php echo e(asset('storage/' . $warehouse->image)); ?>" alt="<?php echo e($warehouse->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <img src="https://c8.alamy.com/comp/2F3MA8Y/interior-of-a-modern-warehouse-storage-of-retail-shop-with-pallet-truck-near-shelves-2F3MA8Y.jpg" alt="Default Warehouse Image" class="w-full h-full object-cover">
                <?php endif; ?>
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo e($warehouse->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e(ucfirst($warehouse->status)); ?>

                    </span>
                </div>
            </div>

            <!-- Warehouse Info -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($warehouse->name); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php echo e($warehouse->address); ?></p>
                
                <!-- Stats -->
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Capacity</p>
                        <p class="text-sm font-semibold text-gray-900"><?php echo e(number_format($warehouse->storage_capacity)); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Current Stock</p>
                        <p class="text-sm font-semibold text-gray-900"><?php echo e(number_format($warehouse->current_stock)); ?></p>
                    </div>
                </div>

                <!-- Utilization Progress Bar -->
                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-500">Utilization</span>
                        <span class="font-semibold text-gray-900"><?php echo e($warehouse->storage_capacity > 0 ? round(($warehouse->current_stock / $warehouse->storage_capacity) * 100) : 0); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full <?php echo e(($warehouse->current_stock / $warehouse->storage_capacity * 100) > 80 ? 'bg-red-600' : (($warehouse->current_stock / $warehouse->storage_capacity * 100) > 60 ? 'bg-yellow-600' : 'bg-green-600')); ?>" style="width: <?php echo e($warehouse->storage_capacity > 0 ? ($warehouse->current_stock / $warehouse->storage_capacity * 100) : 0); ?>%"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex items-center justify-between">
                    <a href="<?php echo e(route('company.warehouses.show', [$tenant, $warehouse])); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View Details →
                    </a>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('company.warehouses.edit', [$tenant, $warehouse])); ?>" class="p-2 text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full rounded-lg bg-white shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No warehouses</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new warehouse location.</p>
            <div class="mt-6">
                <a href="<?php echo e(route('company.warehouses.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Add Warehouse
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Stock Transfer Section -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Stock Transfers</h3>
           <a href="<?php echo e(route('company.stock-movements.index', $tenant)); ?>" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transfer ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentTransfers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            #<?php echo e($transfer->id); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($transfer->item_name); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($transfer->from_warehouse); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($transfer->to_warehouse); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <?php echo e($transfer->quantity); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                <?php echo e($transfer->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($transfer->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-blue-100 text-blue-800')); ?>">
                                <?php echo e(ucfirst($transfer->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($transfer->created_at); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                            No transfers found
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/warehouse/index.blade.php ENDPATH**/ ?>