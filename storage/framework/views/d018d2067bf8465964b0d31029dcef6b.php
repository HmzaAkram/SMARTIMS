

<?php $__env->startSection('title', 'Orders - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Orders
            </h2>
            <p class="mt-1 text-sm text-gray-500">Manage customer orders and track deliveries</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('company.orders.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New Order
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($stats['total_orders'] ?? 0); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Pending Orders</dt>
            <dd class="mt-1 text-3xl font-semibold text-yellow-600"><?php echo e($stats['pending_orders'] ?? 0); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Completed</dt>
            <dd class="mt-1 text-3xl font-semibold text-green-600"><?php echo e($stats['completed_orders'] ?? 0); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">$<?php echo e(number_format($stats['total_revenue'] ?? 0, 2)); ?></dd>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg bg-white shadow p-4">
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label for="order_type" class="block text-sm font-medium text-gray-700">Order Type</label>
                <select name="order_type" id="order_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Types</option>
                    <option value="sales">Sales</option>
                    <option value="purchase">Purchase</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="date_from" id="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="rounded-lg bg-white shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="<?php echo e(route('company.orders.show', [$tenant, $order])); ?>" class="text-sm font-mono font-semibold text-indigo-600 hover:text-indigo-900">
                            <?php echo e($order->order_number); ?>

                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($order->customer->name ?? 'N/A'); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($order->customer->email ?? ''); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 <?php echo e($order->type_badge); ?>">
                            <?php echo e(ucfirst($order->order_type)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($order->order_date->format('M d, Y')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        $<?php echo e(number_format($order->total_amount, 2)); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 <?php echo e($order->status_badge); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="<?php echo e(route('company.orders.show', [$tenant, $order])); ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <?php if(!in_array($order->status, ['delivered', 'cancelled'])): ?>
                        <a href="<?php echo e(route('company.orders.edit', [$tenant, $order])); ?>" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new order.</p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('company.orders.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                New Order
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($orders->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($orders->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/orders/index.blade.php ENDPATH**/ ?>