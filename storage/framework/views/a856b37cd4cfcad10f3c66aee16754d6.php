

<?php $__env->startSection('title', 'Super Admin Dashboard - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="dashboard()" class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Companies -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Companies</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($totalCompanies)); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <?php if($companiesGrowthPercent >= 0): ?>
                        <span class="text-green-300 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?php echo e($companiesGrowthPercent); ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-300 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i> <?php echo e(abs($companiesGrowthPercent)); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-indigo-200 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Active Subscriptions</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($activeSubscriptions)); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-300 text-sm font-medium">
                        <?php echo e($trialingSubscriptions); ?> in trial
                    </span>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Monthly Revenue</p>
                        <p class="text-3xl font-bold mt-2">$<?php echo e(number_format($monthlyRevenue)); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <?php if($revenueGrowthPercent >= 0): ?>
                        <span class="text-green-300 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?php echo e($revenueGrowthPercent); ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-red-300 text-sm font-medium">
                            <i class="fas fa-arrow-down mr-1"></i> <?php echo e(abs($revenueGrowthPercent)); ?>%
                        </span>
                    <?php endif; ?>
                    <span class="text-purple-200 text-sm ml-2">growth</span>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($totalUsers)); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-cyan-300 text-sm font-medium">
                        <?php echo e($platformStats['active_sessions'] ?? 0); ?> active now
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Revenue Overview</h3>
                    <p class="text-gray-600 text-sm mt-1">Monthly recurring revenue (MRR)</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        <i class="fas fa-chart-line mr-1"></i> Last 12 months
                    </span>
                </div>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Companies Growth -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Companies Growth</h3>
                    <p class="text-gray-600 text-sm mt-1">New companies registered</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-building mr-1"></i> Monthly trend
                    </span>
                </div>
            </div>
            <div class="h-72">
                <canvas id="companiesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Companies -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Recent Companies</h3>
                    <p class="text-gray-600 text-sm mt-1">Latest registered companies</p>
                </div>
                <a href="<?php echo e(route('admin.companies.index')); ?>" 
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $recentCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    <?php echo e($company->plan === 'Premium' ? 'bg-purple-100 text-purple-800' : 
                                       ($company->plan === 'Business' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-gray-100 text-gray-800')); ?>">
                                    <?php echo e($company->plan); ?>

                                </span>
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
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                <p class="text-gray-600 text-sm mt-1">Platform activities</p>
            </div>
            <div class="p-4 space-y-4 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 rounded-full <?php echo e($activity['color']); ?> flex items-center justify-center">
                        <i class="fas fa-<?php echo e($activity['icon']); ?> text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900"><?php echo e($activity['title']); ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($activity['description']); ?></p>
                        <p class="text-xs text-gray-400 mt-2"><?php echo e($activity['time']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total Assets</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e(number_format($platformStats['total_assets'] ?? 0)); ?></p>
                </div>
                <i class="fas fa-server text-gray-400 text-xl"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Work Orders</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e(number_format($platformStats['total_work_orders'] ?? 0)); ?></p>
                </div>
                <i class="fas fa-clipboard-list text-blue-200 text-xl"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Pending Invoices</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e(number_format($platformStats['pending_invoices'] ?? 0)); ?></p>
                </div>
                <i class="fas fa-file-invoice text-yellow-200 text-xl"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Active Sessions</p>
                    <p class="text-2xl font-bold mt-1"><?php echo e(number_format($platformStats['active_sessions'] ?? 0)); ?></p>
                </div>
                <i class="fas fa-signal text-green-200 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function dashboard() {
    return {
        init() {
            this.initRevenueChart();
            this.initCompaniesChart();
        },
        
        initRevenueChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($monthLabels, 15, 512) ?>,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: <?php echo json_encode($revenueData, 15, 512) ?>,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: (context) => `$${context.parsed.y.toLocaleString()}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: (value) => '$' + (value / 1000) + 'k'
                            }
                        },
                        x: { 
                            grid: { display: false }
                        }
                    }
                }
            });
        },
        
        initCompaniesChart() {
            const ctx = document.getElementById('companiesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($monthLabels, 15, 512) ?>,
                    datasets: [{
                        label: 'New Companies',
                        data: <?php echo json_encode($companiesData, 15, 512) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.parsed.y} companies`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { stepSize: 5 }
                        },
                        x: { 
                            grid: { display: false }
                        }
                    }
                }
            });
        },
        
        exportReport(type) {
            fetch(`/admin/export-report?type=${type}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create download link
                    const blob = new Blob([JSON.stringify(data.data)], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${type}_report_${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    
                    this.showNotification('Report exported successfully!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Failed to export report', 'error');
            });
        },
        
        showNotification(message, type = 'success') {
            // Implement notification system
            alert(message);
        }
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/dashboard.blade.php ENDPATH**/ ?>