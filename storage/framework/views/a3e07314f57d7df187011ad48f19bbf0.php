

<?php $__env->startSection('title', 'Reports - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="reports()" class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
                <p class="text-gray-600 mt-1">Generate and download various reports</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Report Generator -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Generate Report</h2>
                
                <form action="<?php echo e(route('admin.reports.generate')); ?>" method="POST" target="_blank">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                            <select name="report_type" required 
                                    x-model="reportType"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Report Type</option>
                                <option value="revenue">Revenue Report</option>
                                <option value="users">User Growth Report</option>
                                <option value="companies">Company Growth Report</option>
                                <option value="subscriptions">Subscription Report</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range *</label>
                            <select name="date_range" required 
                                    x-model="dateRange"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        
                        <!-- Custom Date Range -->
                        <div x-show="dateRange === 'custom'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" name="start_date" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="date" name="end_date" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format *</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <input type="radio" name="format" value="pdf" id="format_pdf" class="sr-only" checked>
                                    <label for="format_pdf" 
                                           class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                        <i class="fas fa-file-pdf text-2xl text-red-600 mb-2"></i>
                                        <span class="text-sm font-medium text-gray-900">PDF</span>
                                    </label>
                                </div>
                                
                                <div>
                                    <input type="radio" name="format" value="csv" id="format_csv" class="sr-only">
                                    <label for="format_csv" 
                                           class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                        <i class="fas fa-file-csv text-2xl text-green-600 mb-2"></i>
                                        <span class="text-sm font-medium text-gray-900">CSV</span>
                                    </label>
                                </div>
                                
                                <div>
                                    <input type="radio" name="format" value="excel" id="format_excel" class="sr-only">
                                    <label for="format_excel" 
                                           class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                        <i class="fas fa-file-excel text-2xl text-green-600 mb-2"></i>
                                        <span class="text-sm font-medium text-gray-900">Excel</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition">
                                <i class="fas fa-download mr-2"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Templates -->
        <div class="space-y-6">
            <!-- Quick Reports -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Reports</h2>
                <div class="space-y-3">
                    <a href="#" @click="generateQuickReport('today_revenue')" 
                       class="flex items-center justify-between px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-dollar-sign text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-blue-800">Today's Revenue</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    
                    <a href="#" @click="generateQuickReport('monthly_users')" 
                       class="flex items-center justify-between px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-green-800">Monthly User Growth</span>
                        </div>
                        <i class="fas fa-chevron-right text-green-600"></i>
                    </a>
                    
                    <a href="#" @click="generateQuickReport('active_subscriptions')" 
                       class="flex items-center justify-between px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-purple-600 mr-3"></i>
                            <span class="text-sm font-medium text-purple-800">Active Subscriptions</span>
                        </div>
                        <i class="fas fa-chevron-right text-purple-600"></i>
                    </a>
                    
                    <a href="#" @click="generateQuickReport('pending_payments')" 
                       class="flex items-center justify-between px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-600 mr-3"></i>
                            <span class="text-sm font-medium text-yellow-800">Pending Payments</span>
                        </div>
                        <i class="fas fa-chevron-right text-yellow-600"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Reports</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Revenue Report - Dec 2024</p>
                            <p class="text-xs text-gray-500">Generated 2 hours ago</p>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">User Growth - Last Week</p>
                            <p class="text-xs text-gray-500">Generated 1 day ago</p>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Company Analysis - Nov 2024</p>
                            <p class="text-xs text-gray-500">Generated 3 days ago</p>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Examples -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Report Examples</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Revenue Report Example -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mr-3">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Revenue Report</h3>
                        <p class="text-sm text-gray-500">Detailed revenue analysis</p>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Monthly/Quarterly/Yearly breakdown
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Payment method analysis
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Revenue by company
                    </li>
                </ul>
            </div>

            <!-- User Report Example -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">User Growth Report</h3>
                        <p class="text-sm text-gray-500">User acquisition analysis</p>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Daily/Monthly user growth
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        User distribution by role
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Company-wise user count
                    </li>
                </ul>
            </div>

            <!-- Subscription Report Example -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Subscription Report</h3>
                        <p class="text-sm text-gray-500">Subscription performance</p>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Plan-wise distribution
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Churn rate analysis
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Renewal predictions
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function reports() {
    return {
        reportType: '',
        dateRange: 'this_month',
        
        generateQuickReport(type) {
            let formData = new FormData();
            formData.append('report_type', this.getReportType(type));
            formData.append('date_range', this.getDateRange(type));
            formData.append('format', 'pdf');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            fetch('<?php echo e(route("admin.reports.generate")); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${type}_report_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            });
        },
        
        getReportType(type) {
            const mapping = {
                'today_revenue': 'revenue',
                'monthly_users': 'users',
                'active_subscriptions': 'subscriptions',
                'pending_payments': 'revenue'
            };
            return mapping[type] || 'revenue';
        },
        
        getDateRange(type) {
            const mapping = {
                'today_revenue': 'today',
                'monthly_users': 'this_month',
                'active_subscriptions': 'this_month',
                'pending_payments': 'this_month'
            };
            return mapping[type] || 'this_month';
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/super-admin/reports/index.blade.php ENDPATH**/ ?>