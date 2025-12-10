

<?php $__env->startSection('title', 'Payments Management - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payments Management</h1>
                <p class="text-gray-600 mt-1">Manage all payments across all companies</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?php echo e(route('admin.payments.create')); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    <i class="fas fa-plus mr-2"></i> Add Payment
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Payments</p>
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
                    <p class="text-gray-500 text-sm">Completed</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['completed']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['pending']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Amount</p>
                    <p class="text-2xl font-bold mt-1">$<?php echo e(number_format($stats['total_amount'])); ?></p>
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
                       placeholder="Search invoices or transactions..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                    <option value="refunded" <?php echo e(request('status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="<?php echo e(route('admin.payments.index')); ?>" 
                   class="w-full md:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900"><?php echo e($payment->invoice_number); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($payment->transaction_id); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900"><?php echo e($payment->tenant->name); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">$<?php echo e($payment->amount); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($payment->currency); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?php echo e($payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                   'bg-gray-100 text-gray-800'))); ?>">
                                <span class="w-2 h-2 rounded-full mr-2 
                                    <?php echo e($payment->status === 'completed' ? 'bg-green-500' : 
                                       ($payment->status === 'pending' ? 'bg-yellow-500' : 
                                       ($payment->status === 'failed' ? 'bg-red-500' : 
                                       'bg-gray-500'))); ?>"></span>
                                <?php echo e(ucfirst($payment->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?php echo e(ucfirst($payment->payment_method)); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($payment->created_at->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('admin.payments.show', $payment->id)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.payments.edit', $payment->id)); ?>" 
                                   class="text-gray-600 hover:text-gray-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if($payment->status === 'pending'): ?>
                                <form action="<?php echo e(route('admin.payments.mark-paid', $payment->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as Paid">
                                        <i class="fas fa-check"></i>
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
                                <i class="fas fa-receipt text-4xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">No payments found</p>
                            <p class="text-gray-500 text-sm mt-1">Start by adding your first payment</p>
                            <a href="<?php echo e(route('admin.payments.create')); ?>" 
                               class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Payment
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($payments->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($payments->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/payments/index.blade.php ENDPATH**/ ?>