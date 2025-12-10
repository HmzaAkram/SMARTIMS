

<?php $__env->startSection('title', 'Companies Management - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="companies()" class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Companies Management</h1>
                <p class="text-gray-600 mt-1">Manage all registered companies</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="<?php echo e(route('admin.companies.create')); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                    <i class="fas fa-plus mr-2"></i> Add Company
                </a>
                <a href="<?php echo e(route('admin.companies.export')); ?>" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Companies</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['total']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-building"></i>
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
                    <p class="text-gray-500 text-sm">Suspended</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($stats['suspended']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-ban"></i>
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
                    <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Created Date</option>
                    <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name</option>
                    <option value="users_count" <?php echo e(request('sort') == 'users_count' ? 'selected' : ''); ?>>Users</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="<?php echo e(route('admin.companies.index')); ?>" 
                   class="w-full md:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Companies Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Domain</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-sm"><?php echo e(strtoupper(substr($company->name, 0, 2))); ?></span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($company->name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($company->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900"><?php echo e($company->domain); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?php echo e($company->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($company->status === 'trialing' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800')); ?>">
                                <span class="w-2 h-2 rounded-full mr-2 
                                    <?php echo e($company->status === 'active' ? 'bg-green-500' : 
                                       ($company->status === 'trialing' ? 'bg-yellow-500' : 
                                       'bg-red-500')); ?>"></span>
                                <?php echo e(ucfirst($company->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-users text-gray-400 mr-2"></i>
                                <span class="font-medium"><?php echo e($company->users_count); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($company->created_at->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('admin.companies.show', $company->id)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.companies.edit', $company->id)); ?>" 
                                   class="text-gray-600 hover:text-gray-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if($company->status === 'active'): ?>
                                <form action="<?php echo e(route('admin.companies.suspend', $company->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Suspend">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                                <?php elseif($company->status === 'suspended'): ?>
                                <form action="<?php echo e(route('admin.companies.activate', $company->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Activate">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.companies.destroy', $company->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to delete this company?')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-building text-4xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">No companies found</p>
                            <p class="text-gray-500 text-sm mt-1">Start by adding your first company</p>
                            <a href="<?php echo e(route('admin.companies.create')); ?>" 
                               class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Company
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($companies->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($companies->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function companies() {
    return {
        // Add any JavaScript functionality here
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/companies/index.blade.php ENDPATH**/ ?>