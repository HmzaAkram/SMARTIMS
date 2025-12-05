

<?php $__env->startSection('title', 'Super Admin Dashboard - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">
                    Super Admin Dashboard
                </h2>
                <p class="mt-2 text-sm text-gray-600">Welcome back, <?php echo e(auth()->user()->name); ?>! Here's what's happening today.</p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                <button type="button" onclick="exportReport()" class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-gray-400 transition-all duration-150">
                    <svg class="mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Report
                </button>
                <button type="button" onclick="addCompany()" class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-indigo-700 hover:to-indigo-800 transition-all duration-150">
                    <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Add Company
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Companies -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-4 flex items-center justify-between h-24 hover:shadow-xl transition-shadow duration-200">
            <div class="flex flex-col justify-center">
                <p class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Total Companies</p>
                <div class="flex items-baseline mt-1">
                    <p class="text-white text-2xl font-bold"><?php echo e($totalCompanies); ?></p>
                    <?php if(isset($companiesGrowthPercent) && $companiesGrowthPercent > 0): ?>
                        <span class="ml-2 text-indigo-200 text-xs font-bold">↑<?php echo e($companiesGrowthPercent); ?>%</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="bg-white/20 rounded-full p-2.5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 flex items-center justify-between h-24 hover:shadow-xl transition-shadow duration-200">
            <div class="flex flex-col justify-center">
                <p class="text-green-100 text-xs font-semibold uppercase tracking-wider">Active Subscriptions</p>
                <p class="text-white text-2xl font-bold mt-1"><?php echo e($activeSubscriptions); ?></p>
            </div>
            <div class="bg-white/20 rounded-full p-2.5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-4 flex items-center justify-between h-24 hover:shadow-xl transition-shadow duration-200">
            <div class="flex flex-col justify-center">
                <p class="text-yellow-100 text-xs font-semibold uppercase tracking-wider">Monthly Revenue</p>
                <p class="text-white text-2xl font-bold mt-1">$<?php echo e(number_format($monthlyRevenue)); ?></p>
            </div>
            <div class="bg-white/20 rounded-full p-2.5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 flex items-center justify-between h-24 hover:shadow-xl transition-shadow duration-200">
            <div class="flex flex-col justify-center">
                <p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">Total Users</p>
                <p class="text-white text-2xl font-bold mt-1"><?php echo e($totalUsers); ?></p>
            </div>
            <div class="bg-white/20 rounded-full p-2.5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Revenue Chart -->
        <div class="rounded-lg bg-white p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <p class="text-sm text-gray-500 mt-1">Monthly revenue trends</p>
                </div>
                <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Last 6 months</span>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Companies Growth Chart -->
        <div class="rounded-lg bg-white p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Companies Growth</h3>
                    <p class="text-sm text-gray-500 mt-1">New companies registered</p>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full">Last 6 months</span>
            </div>
            <div class="h-72">
                <canvas id="companiesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Companies Table -->
    <div class="rounded-lg bg-white shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Recent Companies</h3>
                    <p class="text-sm text-indigo-100 mt-1">Latest registered companies</p>
                </div>
                <button onclick="viewAllCompanies()" class="text-sm font-medium text-white hover:text-indigo-100 transition">
                    View All
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Domain</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-indigo-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                                        <span class="text-white font-bold text-base"><?php echo e(strtoupper(substr($company->name, 0, 2))); ?></span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900"><?php echo e($company->name); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($company->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium"><?php echo e($company->domain); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800">
                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24 .588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <?php echo e($company->plan); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?php echo e($company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <span class="mr-1 h-1.5 w-1.5 rounded-full <?php echo e($company->status === 'active' ? 'bg-green-600' : 'bg-red-600'); ?>"></span>
                                <?php echo e(ucfirst($company->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-900">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <?php echo e($company->users_count); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($company->created_at->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="viewCompany(<?php echo e($company->id); ?>)" class="text-indigo-600 hover:text-indigo-900 font-semibold transition">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="mt-4 text-base font-medium text-gray-900">No companies found</p>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first company</p>
                            <button onclick="addCompany()" class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Add Company
                            </button>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Button Functions
    function exportReport() {
        alert('Exporting report... (Will be implemented)');
    }

    function addCompany() {
        alert('Opening Add Company modal...');
    }

    function viewAllCompanies() {
        alert('Redirecting to Companies list...');
    }

    function viewCompany(id) {
        alert('Viewing company ID: ' + id);
    }

    // Chart.js - Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']); ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode($revenueData ?? [30000, 35000, 32000, 40000, 42000, 45600]); ?>,
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(99, 102, 241)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: ctx => 'Revenue: $' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: v => '$' + (v / 1000) + 'k'
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart.js - Companies Bar Chart
    const companiesCtx = document.getElementById('companiesChart').getContext('2d');
    new Chart(companiesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']); ?>,
            datasets: [{
                label: 'New Companies',
                data: <?php echo json_encode($companiesGrowth ?? [12, 19, 15, 25, 22, 30]); ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                hoverBackgroundColor: 'rgba(99, 102, 241, 1)',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 5 }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/dashboard.blade.php ENDPATH**/ ?>