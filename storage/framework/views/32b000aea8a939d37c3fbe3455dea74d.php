

<?php $__env->startSection('title', 'Customers - SMARTIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Customers
            </h2>
            <p class="mt-1 text-sm text-gray-500">Manage your customers and their information</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('company.customers.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New Customer
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Customers</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($stats['total_customers']); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Active Customers</dt>
            <dd class="mt-1 text-3xl font-semibold text-green-600"><?php echo e($stats['active_customers']); ?></dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Inactive Customers</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-400"><?php echo e($stats['inactive_customers']); ?></dd>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="rounded-lg bg-white shadow p-4">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" placeholder="Search customers..." class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all" <?php echo e(request('status') == 'all' ? 'selected' : ''); ?>>All Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="sr-only">Type</label>
                    <select name="type" id="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all" <?php echo e(request('type') == 'all' ? 'selected' : ''); ?>>All Types</option>
                        <option value="retail" <?php echo e(request('type') == 'retail' ? 'selected' : ''); ?>>Retail</option>
                        <option value="wholesale" <?php echo e(request('type') == 'wholesale' ? 'selected' : ''); ?>>Wholesale</option>
                        <option value="corporate" <?php echo e(request('type') == 'corporate' ? 'selected' : ''); ?>>Corporate</option>
                        <option value="government" <?php echo e(request('type') == 'government' ? 'selected' : ''); ?>>Government</option>
                        <option value="walkin" <?php echo e(request('type') == 'walkin' ? 'selected' : ''); ?>>Walk-in</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <button type="submit" class="w-full rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                        Filter
                    </button>
                    <a href="<?php echo e(route('company.customers.index', $tenant)); ?>" class="w-full rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300 text-center">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="rounded-lg bg-white shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <a href="<?php echo e(route('company.customers.show', [$tenant, $customer])); ?>" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                        <?php echo e($customer->name); ?>

                                    </a>
                                    <div class="text-xs text-gray-500"><?php echo e($customer->customer_code); ?></div>
                                    <?php if($customer->company_name): ?>
                                    <div class="text-sm text-gray-500"><?php echo e($customer->company_name); ?></div>
                                    <?php endif; ?>
                                    <?php if($customer->gst_number): ?>
                                    <div class="text-xs text-gray-400">GST: <?php echo e($customer->gst_number); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e($customer->contact_person ?? '-'); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($customer->email); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($customer->phone); ?></div>
                            <?php if($customer->mobile): ?>
                            <div class="text-sm text-gray-500">M: <?php echo e($customer->mobile); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium <?php echo e($customer->current_balance < 0 ? 'text-green-600' : ($customer->current_balance > 0 ? 'text-red-600' : 'text-gray-900')); ?>">
                                ₹ <?php echo e(number_format($customer->current_balance, 2)); ?>

                            </div>
                            <?php if($customer->credit_limit): ?>
                            <div class="text-xs text-gray-500">Credit Limit: ₹ <?php echo e(number_format($customer->credit_limit, 2)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo e($customer->customer_type_badge); ?>">
                                <?php echo e($customer->customer_type_text); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo e($customer->status_badge); ?>">
                                <?php echo e($customer->status_text); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="<?php echo e(route('company.customers.show', [$tenant, $customer])); ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="<?php echo e(route('company.customers.edit', [$tenant, $customer])); ?>" class="text-gray-600 hover:text-gray-900">Edit</a>
                            <button onclick="toggleStatus(<?php echo e($customer->id); ?>)" class="text-gray-600 hover:text-gray-900">
                                <?php echo e($customer->is_active ? 'Deactivate' : 'Activate'); ?>

                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No customers</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new customer.</p>
                            <div class="mt-6">
                                <a href="<?php echo e(route('company.customers.create', $tenant)); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    New Customer
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($customers->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($customers->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleStatus(customerId) {
    if (confirm('Are you sure you want to change the customer status?')) {
        fetch(`/company/<?php echo e($tenant); ?>/customers/${customerId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update status');
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/customers/index.blade.php ENDPATH**/ ?>