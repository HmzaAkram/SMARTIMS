@extends('layouts.company')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white tracking-tight">Overview</h1>
        </div>
        <div class="flex items-center gap-3">
             <a href="{{ route('company.items.create', ['tenant' => request()->route('tenant')]) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 text-[13px] font-medium rounded-md shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Items -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <h3 class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_items'] ?? 0 }}</span>
                <span class="text-[13px] font-medium text-green-600 dark:text-green-400 flex items-center">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['item_growth'] ?? 0 }}%
                </span>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <h3 class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Low Stock Alerts</h3>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['low_stock'] ?? 0 }}</span>
                @if(($stats['low_stock'] ?? 0) > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400">
                        Needs Attention
                    </span>
                @endif
            </div>
        </div>

        <!-- Stock Value -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <h3 class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Total Stock Value</h3>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['value'] ?? 0) }}</span>
                <span class="text-[13px] font-medium text-green-600 dark:text-green-400 flex items-center">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['value_growth'] ?? 0 }}%
                </span>
            </div>
        </div>

        <!-- Active Warehouses -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <h3 class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Active Warehouses</h3>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $warehouses ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Inventory Movement</h3>
                <!-- Simple Dropdown -->
                <div class="relative">
                    <select class="appearance-none bg-transparent border-none text-[13px] font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white cursor-pointer focus:ring-0 pr-6">
                        <option>Last 30 Days</option>
                        <option>Last 7 Days</option>
                    </select>
                </div>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Stock Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-6">Stock Status</h3>
            <div class="relative h-48 mb-6">
                <canvas id="stockPieChart"></canvas>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span class="text-[13px] text-gray-600 dark:text-gray-300">In Stock</span>
                    </div>
                    <span class="text-[13px] font-semibold text-gray-900 dark:text-white">{{ $stockDistribution['in_stock'] ?? 0 }}%</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-[13px] text-gray-600 dark:text-gray-300">Low Stock</span>
                    </div>
                    <span class="text-[13px] font-semibold text-gray-900 dark:text-white">{{ $stockDistribution['low_stock'] ?? 0 }}%</span>
                </div>
                 <div class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span class="text-[13px] text-gray-600 dark:text-gray-300">Out of Stock</span>
                    </div>
                    <span class="text-[13px] font-semibold text-gray-900 dark:text-white">{{ $stockDistribution['out_of_stock'] ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Stock Movements -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Recent Movements</h3>
                <a href="{{ route('company.stock-movements.index', ['tenant' => request()->route('tenant')]) }}" class="text-[13px] text-gray-500 hover:text-gray-900 dark:hover:text-white font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400">Item</th>
                            <th class="px-5 py-3 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400">Type</th>
                            <th class="px-5 py-3 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400 text-right">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recentMovements as $movement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <p class="text-[13px] font-medium text-gray-900 dark:text-white">{{ $movement->item->name ?? 'Unknown' }}</p>
                                <p class="text-[11px] text-gray-500">{{ $movement->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $movement->type === 'in' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="text-[13px] font-medium {{ $movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-[13px] text-gray-500">
                                No recent movements found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alerts -->
         <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
             <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Low Stock</h3>
                    @if(($stats['low_stock'] ?? 0) > 0)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                        {{ $stats['low_stock'] }}
                    </span>
                    @endif
                </div>
                 <a href="/company/{{ request()->route('tenant') }}/inventory/items?filter=low_stock" class="text-[13px] text-gray-500 hover:text-gray-900 dark:hover:text-white font-medium">View All</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($lowStockAlerts as $item)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                    <div class="flex items-center gap-3">
                         <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            @if($item->image)
                                <img src="{{ $item->image }}" class="h-full w-full object-cover rounded" alt="">
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[13px] font-medium text-gray-900 dark:text-white">{{ $item->name }}</p>
                            <p class="text-[11px] text-gray-500">SKU: {{ $item->sku }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-red-600 dark:text-red-400">{{ $item->quantity }}</p>
                        <p class="text-[11px] text-gray-400">Min: {{ $item->min_quantity }}</p>
                    </div>
                </div>
                @empty
                 <div class="px-5 py-8 text-center">
                    <p class="text-[13px] text-gray-500">All items are well stocked</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const chartData = {
    labels: @json($chartLabels),
    stockIn: @json($stockInData),
    stockOut: @json($stockOutData),
    stockDistribution: @json([
        $stockDistribution['in_stock'] ?? 0,
        $stockDistribution['low_stock'] ?? 0,
        $stockDistribution['out_of_stock'] ?? 0
    ])
};

document.addEventListener('alpine:init', () => {
    setTimeout(() => {
        // Inventory Chart
        const inventoryCtx = document.getElementById('inventoryChart');
        if (inventoryCtx) {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#374151' : '#f3f4f6';
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            
            new Chart(inventoryCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Stock In',
                        data: chartData.stockIn,
                        borderColor: '#10b981', // Emerald 500
                        backgroundColor: 'rgba(16, 185, 129, 0)',
                        borderWidth: 2,
                        tension: 0,
                        pointRadius: 0,
                        pointHoverRadius: 4
                    }, {
                        label: 'Stock Out',
                        data: chartData.stockOut,
                        borderColor: '#ef4444', // Red 500
                        backgroundColor: 'rgba(239, 68, 68, 0)',
                        borderWidth: 2,
                        tension: 0,
                        pointRadius: 0,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false // Minimalist: hide legend, user context is clear
                        },
                        tooltip: {
                             backgroundColor: isDark ? '#1f2937' : '#ffffff',
                             titleColor: isDark ? '#ffffff' : '#111827',
                             bodyColor: isDark ? '#d1d5db' : '#4b5563',
                             borderColor: isDark ? '#374151' : '#e5e7eb',
                             borderWidth: 1,
                             padding: 10,
                             cornerRadius: 6,
                             displayColors: true,
                             titleFont: { size: 11 },
                             bodyFont: { size: 11 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, borderDash: [2, 2], drawBorder: false },
                            ticks: { color: textColor, font: { size: 10 }, padding: 10 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 }, padding: 10 }
                        }
                    }
                }
            });
        }

        // Pie Chart
        const stockPieCtx = document.getElementById('stockPieChart');
        if (stockPieCtx) {
             new Chart(stockPieCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        data: chartData.stockDistribution,
                        backgroundColor: ['#6366f1', '#f59e0b', '#ef4444'], // Indigo, Amber, Red
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }, 100);
});
</script>
@endpush
@endsection