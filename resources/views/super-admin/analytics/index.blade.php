@extends('layouts.super-admin')

@section('title', 'Analytics Dashboard - SmartIMS')

@section('content')
<div x-data="analytics()" class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="text-gray-600 mt-1">Comprehensive insights and metrics</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form method="GET" class="flex items-center space-x-3">
                    <div>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                               class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <span class="text-gray-500">to</span>
                    <div>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-filter mr-2"></i> Apply
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm">Monthly Revenue (MRR)</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($metrics['mrr']) }}</p>
                </div>
                <i class="fas fa-dollar-sign text-2xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Annual Revenue (ARR)</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($metrics['arr']) }}</p>
                </div>
                <i class="fas fa-chart-line text-2xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Churn Rate</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($metrics['churn_rate'], 2) }}%</p>
                </div>
                <i class="fas fa-chart-pie text-2xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Avg Revenue Per User</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($metrics['arpu'], 2) }}</p>
                </div>
                <i class="fas fa-users text-2xl opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                    <p class="text-gray-600 text-sm mt-1">Daily revenue from {{ $startDate }} to {{ $endDate }}</p>
                </div>
                <button @click="exportData('revenue')" 
                        class="text-sm text-indigo-600 hover:text-indigo-900">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                    <p class="text-gray-600 text-sm mt-1">New users registered daily</p>
                </div>
                <button @click="exportData('users')" 
                        class="text-sm text-indigo-600 hover:text-indigo-900">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Plan -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue by Plan</h3>
                <span class="text-sm text-gray-500">{{ count($revenueByPlan) }} plans</span>
            </div>
            <div class="space-y-4">
                @foreach($revenueByPlan as $plan)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3
                            {{ $plan->plan === 'enterprise' ? 'bg-purple-500' : 
                               ($plan->plan === 'premium' ? 'bg-blue-500' : 
                               ($plan->plan === 'growth' ? 'bg-green-500' : 
                               'bg-gray-500')) }}"></div>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($plan->plan) }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">${{ number_format($plan->revenue) }}</div>
                        <div class="text-xs text-gray-500">{{ $plan->count }} companies</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Company Distribution -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Company Distribution</h3>
                <span class="text-sm text-gray-500">By status</span>
            </div>
            <div class="h-48">
                <canvas id="companyDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Revenue -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Revenue</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($revenueData->take(7) as $revenue)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $revenue->date }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($revenue->total, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center text-sm {{ $revenue->total > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @if($revenue->total > 0)
                                    <i class="fas fa-arrow-up mr-1"></i> 
                                    @else
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    @endif
                                    {{ $revenue->total > 0 ? 'Positive' : 'Negative' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Distribution</h3>
            <div class="space-y-4">
                @foreach($userDistribution as $role => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full 
                            {{ $role === 'admins' ? 'bg-purple-100 text-purple-600' : 
                               ($role === 'managers' ? 'bg-green-100 text-green-600' : 
                               'bg-blue-100 text-blue-600') }} flex items-center justify-center mr-3">
                            <i class="fas 
                                {{ $role === 'admins' ? 'fa-shield-alt' : 
                                   ($role === 'managers' ? 'fa-user-tie' : 
                                   'fa-user') }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($role) }}</p>
                            <p class="text-xs text-gray-500">{{ $count }} users</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $totalUsers = array_sum($userDistribution) > 0 ? round(($count / array_sum($userDistribution)) * 100) : 0 }}%
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function analytics() {
    return {
        init() {
            this.initRevenueChart();
            this.initUserGrowthChart();
            this.initCompanyDistributionChart();
        },
        
        initRevenueChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const dates = @json($revenueData->pluck('date'));
            const revenue = @json($revenueData->pluck('total'));
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: revenue,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        },
        
        initUserGrowthChart() {
            const ctx = document.getElementById('userGrowthChart').getContext('2d');
            const dates = @json($userGrowth->pluck('date'));
            const counts = @json($userGrowth->pluck('count'));
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'New Users',
                        data: counts,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        },
        
        initCompanyDistributionChart() {
            const ctx = document.getElementById('companyDistributionChart').getContext('2d');
            const data = @json($companyDistribution);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        exportData(type) {
            window.location.href = `{{ route('admin.analytics.export') }}?type=${type}&start_date={{ $startDate }}&end_date={{ $endDate }}`;
        }
    }
}
</script>
@endpush
@endsection