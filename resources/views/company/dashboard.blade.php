@extends('layouts.company')

@section('title', 'Dashboard - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Dashboard Overview</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" 
                    class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all-300 hover:shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </button>
            <a href="/company/{{ request()->route('tenant') }}/items/create" 
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-gray text-sm font-medium rounded-xl transition-all-300 hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                </svg>
                Add Item
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Items -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Items</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_items'] ?? 1245 }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/>
                </svg>
                <span>5.2% from last month</span>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Low Stock Alerts</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['low_stock'] ?? 23 }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20">
                    Needs attention
                </span>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100">Stock Value</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($stats['value'] ?? 125600) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/>
                </svg>
                <span>8.1% from last month</span>
            </div>
        </div>

        <!-- Warehouses -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Active Warehouses</p>
                    <p class="text-3xl font-bold mt-2">{{ $warehouses ?? 5 }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20">
                    Active locations
                </span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Inventory Overview Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Overview</h3>
                <select class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-auto px-4 py-2.5 transition-all-300">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-72">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Stock Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Stock Distribution</h3>
            <div class="h-56 mb-6">
                <canvas id="stockPieChart"></canvas>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-3"></span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">In Stock</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">68%</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Low Stock</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">22%</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-3"></span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Out of Stock</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Low Stock Items -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Stock Movements -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Stock Movements</h3>
                    <a href="{{ route('company.stock-movements.index', ['tenant' => request()->route('tenant')]) }}" 
                       class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-all-300">
                        View all
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentMovements as $movement)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all-300">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl {{ $movement->type === 'in' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center">
                            <svg class="h-6 w-6 {{ $movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($movement->type === 'in')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $movement->item->name ?? 'Unknown Item' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $movement->warehouse ? $movement->warehouse->name : 'Warehouse #' . $movement->warehouse_id }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $movement->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No recent movements</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Low Stock Alerts</h3>
                    @if(($stats['low_stock'] ?? 0) > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        {{ $stats['low_stock'] ?? 0 }} items
                    </span>
                    @endif
                </div>
                <a href="/company/{{ request()->route('tenant') }}/inventory/items?filter=low_stock" 
                   class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-all-300">
                    View all
                </a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($lowStockAlerts as $item)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all-300">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center overflow-hidden">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                            @else
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $item->sku }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600 dark:text-red-400">{{ $item->quantity }} left</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $item->min_quantity }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">All items are well stocked!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Top Categories -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Categories</h3>
            <div class="space-y-4">
                @foreach(['Electronics', 'Office Supplies', 'Raw Materials'] as $category)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $category }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-24 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" style="width: {{ rand(60, 90) }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ rand(200, 500) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Orders</h3>
            <div class="space-y-3">
                @foreach(range(1, 3) as $i)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Order #{{ 1000 + $i }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->subHours($i)->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        Completed
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Storage</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">68%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" style="width: 68%"></div>
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Users</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ rand(5, 20) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Uptime</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">99.9%</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Wait for Alpine.js to be ready
document.addEventListener('alpine:init', () => {
    // Initialize charts when they are visible
    setTimeout(() => {
        // Inventory Overview Chart
        const inventoryCtx = document.getElementById('inventoryChart');
        if (inventoryCtx) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            
            new Chart(inventoryCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Stock In',
                        data: [150, 200, 180, 220, 250, 210, 190],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }, {
                        label: 'Stock Out',
                        data: [100, 120, 150, 130, 140, 160, 120],
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: isDarkMode ? '#fff' : '#374151',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        },
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    }
                }
            });
        }

        // Stock Distribution Pie Chart
        const stockPieCtx = document.getElementById('stockPieChart');
        if (stockPieCtx) {
            new Chart(stockPieCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        data: [68, 22, 10],
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(234, 179, 8)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 0,
                        borderRadius: 8,
                        spacing: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }, 100);
});
</script>
@endpush
@endsection