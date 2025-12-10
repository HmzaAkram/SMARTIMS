

<?php $__env->startSection('title', 'Subscriptions Management - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscriptions Management</h1>
                <p class="text-gray-600 mt-1">Manage all company subscriptions</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?php echo e(route('admin.subscriptions.create')); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    <i class="fas fa-plus mr-2"></i> Add Subscription
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Subscriptions</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['total']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['active']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Trialing</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['trialing']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Monthly Revenue</p>
                    <p class="text-2xl font-bold mt-1">$<?php echo e(number_format($stats['revenue'])); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Search companies..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="trialing" <?php echo e(request('status') == 'trialing' ? 'selected' : ''); ?>>Trialing</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                <select name="plan" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Plans</option>
                    <option value="starter" <?php echo e(request('plan') == 'starter' ? 'selected' : ''); ?>>Starter</option>
                    <option value="growth" <?php echo e(request('plan') == 'growth' ? 'selected' : ''); ?>>Growth</option>
                    <option value="premium" <?php echo e(request('plan') == 'premium' ? 'selected' : ''); ?>>Premium</option>
                    <option value="enterprise" <?php echo e(request('plan') == 'enterprise' ? 'selected' : ''); ?>>Enterprise</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="<?php echo e(route('admin.subscriptions.index')); ?>" 
                   class="w-full md:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Billing Cycle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-bold"><?php echo e(strtoupper(substr($subscription->tenant->name, 0, 2))); ?></span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($subscription->tenant->name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($subscription->tenant->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?php echo e($subscription->plan_name === 'enterprise' ? 'bg-purple-100 text-purple-800' : 
                                   ($subscription->plan_name === 'premium' ? 'bg-blue-100 text-blue-800' : 
                                   ($subscription->plan_name === 'growth' ? 'bg-green-100 text-green-800' : 
                                   'bg-gray-100 text-gray-800'))); ?>">
                                <?php echo e(ucfirst($subscription->plan_name)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?php echo e($subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($subscription->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800')); ?>">
                                <span class="w-2 h-2 rounded-full mr-2 
                                    <?php echo e($subscription->status === 'active' ? 'bg-green-500' : 
                                       ($subscription->status === 'trialing' ? 'bg-yellow-500' : 
                                       'bg-red-500')); ?>"></span>
                                <?php echo e(ucfirst($subscription->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">$<?php echo e($subscription->price); ?></div>
                            <div class="text-sm text-gray-500">/month</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?php echo e(ucfirst($subscription->billing_cycle)); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if($subscription->ends_at): ?>
                                <?php echo e($subscription->ends_at->format('M d, Y')); ?>

                            <?php else: ?>
                                <span class="text-gray-400">Never</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('admin.subscriptions.show', $subscription->id)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.subscriptions.edit', $subscription->id)); ?>" 
                                   class="text-gray-600 hover:text-gray-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if($subscription->status === 'active'): ?>
                                <form action="<?php echo e(route('admin.subscriptions.cancel', $subscription->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Cancel">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                <?php elseif($subscription->status === 'cancelled'): ?>
                                <form action="<?php echo e(route('admin.subscriptions.renew', $subscription->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Renew">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-credit-card text-4xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">No subscriptions found</p>
                            <p class="text-gray-500 text-sm mt-1">Start by adding your first subscription</p>
                            <a href="<?php echo e(route('admin.subscriptions.create')); ?>" 
                               class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Subscription
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($subscriptions->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($subscriptions->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/subscriptions/index.blade.php ENDPATH**/ ?>