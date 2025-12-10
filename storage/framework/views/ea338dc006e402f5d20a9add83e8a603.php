

<?php $__env->startSection('title', $company->name . ' - Company Details - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold"><?php echo e(strtoupper(substr($company->name, 0, 2))); ?></span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($company->name); ?></h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            <?php echo e($company->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($company->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-red-100 text-red-800')); ?>">
                            <span class="w-2 h-2 rounded-full mr-2 
                                <?php echo e($company->status === 'active' ? 'bg-green-500' : 
                                   ($company->status === 'trialing' ? 'bg-yellow-500' : 
                                   'bg-red-500')); ?>"></span>
                            <?php echo e(ucfirst($company->status)); ?>

                        </span>
                        <span class="text-gray-600">
                            <i class="fas fa-globe mr-1"></i> <?php echo e($company->domain); ?>.smartims.test
                        </span>
                        <span class="text-gray-600">
                            <i class="fas fa-database mr-1"></i> <?php echo e($company->database); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="<?php echo e(route('admin.companies.edit', $company->id)); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <?php if($company->status === 'active'): ?>
                <form action="<?php echo e(route('admin.companies.suspend', $company->id)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                        <i class="fas fa-pause mr-2"></i> Suspend
                    </button>
                </form>
                <?php elseif($company->status === 'suspended'): ?>
                <form action="<?php echo e(route('admin.companies.activate', $company->id)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-play mr-2"></i> Activate
                    </button>
                </form>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.companies.index')); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($company->users->count()); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Subscription Plan</p>
                    <p class="text-lg font-bold mt-1"><?php echo e($company->subscription->plan_name ?? 'N/A'); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Monthly Cost</p>
                    <p class="text-2xl font-bold mt-1">$<?php echo e($company->subscription->price ?? 0); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Created On</p>
                    <p class="text-lg font-bold mt-1"><?php echo e($company->created_at->format('M d, Y')); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Company Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company Name</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->name); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->email); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->phone ?? 'Not provided'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Domain</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->domain); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Database</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->database); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    <?php echo e($company->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($company->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800')); ?>">
                                    <?php echo e(ucfirst($company->status)); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Details -->
            <?php if($company->subscription): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Details</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Plan Name</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->subscription->plan_name); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Price</label>
                            <p class="mt-1 text-gray-900">$<?php echo e($company->subscription->price); ?>/month</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Billing Cycle</label>
                            <p class="mt-1 text-gray-900"><?php echo e(ucfirst($company->subscription->billing_cycle)); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    <?php echo e($company->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($company->subscription->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800')); ?>">
                                    <?php echo e(ucfirst($company->subscription->status)); ?>

                                </span>
                            </p>
                        </div>
                        <?php if($company->subscription->trial_ends_at): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Trial Ends</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->subscription->trial_ends_at->format('M d, Y')); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if($company->subscription->ends_at): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subscription Ends</label>
                            <p class="mt-1 text-gray-900"><?php echo e($company->subscription->ends_at->format('M d, Y')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Plan Features -->
                    <?php if($company->subscription->features): ?>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Plan Features</label>
                        <div class="grid grid-cols-2 gap-3">
                            <?php $__currentLoopData = $company->subscription->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="text-sm text-gray-700"><?php echo e(ucfirst($feature)); ?>: <?php echo e($value); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Payments -->
            <?php if($company->payments->count() > 0): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Payments</h2>
                    <a href="<?php echo e(route('admin.payments.index')); ?>?search=<?php echo e($company->name); ?>" 
                       class="text-sm text-indigo-600 hover:text-indigo-900">
                        View All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $company->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($payment->invoice_number); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900">$<?php echo e($payment->amount); ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs 
                                        <?php echo e($payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800')); ?>">
                                        <?php echo e(ucfirst($payment->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($payment->created_at->format('M d, Y')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Company Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Company Users</h2>
                    <span class="text-sm text-gray-500"><?php echo e($company->users->count()); ?> users</span>
                </div>
                <div class="space-y-3">
                    <?php $__currentLoopData = $company->users->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-white text-xs font-bold"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($user->email); ?></p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded 
                            <?php echo e($user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                               'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e(ucfirst($user->role)); ?>

                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <?php if($company->subscription && $company->subscription->status === 'trialing'): ?>
                    <form action="<?php echo e(route('admin.companies.reset-trial', $company->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center justify-between px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-redo text-yellow-600 mr-3"></i>
                                <span class="text-sm font-medium text-yellow-800">Reset Trial Period</span>
                            </div>
                            <i class="fas fa-chevron-right text-yellow-600"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <a href="#" class="flex items-center justify-between px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">Send Email</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    
                    <a href="#" class="flex items-center justify-between px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">View Analytics</span>
                        </div>
                        <i class="fas fa-chevron-right text-green-600"></i>
                    </a>
                    
                    <form action="<?php echo e(route('admin.companies.destroy', $company->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this company? This action cannot be undone.')"
                                class="w-full flex items-center justify-between px-4 py-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-trash text-red-600 mr-3"></i>
                                <span class="text-sm font-medium text-red-800">Delete Company</span>
                            </div>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/companies/show.blade.php ENDPATH**/ ?>