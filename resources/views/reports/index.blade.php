@extends('layouts.app')

@section('title', 'Reports - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
            Reports & Analytics
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Generate and export detailed reports for your inventory</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <button type="button" class="relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Inventory Report</span>
        </button>

        <button type="button" class="relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
            <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Warehouse Report</span>
        </button>

        <button type="button" class="relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Stock Movement</span>
        </button>

        <button type="button" class="relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Valuation Report</span>
        </button>
    </div>

    <!-- Report Generator -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generate Custom Report</h3>
        </div>
        <div class="p-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Report Type -->
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-900 dark:text-white">Report Type</label>
                        <select id="report_type" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option>Inventory Summary</option>
                            <option>Stock Movement</option>
                            <option>Low Stock Items</option>
                            <option>Warehouse Performance</option>
                            <option>Expiring Items</option>
                            <option>Stock Valuation</option>
                            <option>Audit Trail</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-900 dark:text-white">Date Range</label>
                        <select id="date_range" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option>Today</option>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                            <option>Custom Range</option>
                        </select>
                    </div>

                    <!-- Warehouse Filter -->
                    <div>
                        <label for="warehouse" class="block text-sm font-medium text-gray-900 dark:text-white">Warehouse</label>
                        <select id="warehouse" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses ?? [] as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Export Format -->
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-900 dark:text-white">Export Format</label>
                        <select id="format" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel (XLSX)</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Filters -->
                <div x-data="{ showFilters: false }">
                    <button @click="showFilters = !showFilters" type="button" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        <span x-show="!showFilters">+ Show Advanced Filters</span>
                        <span x-show="showFilters">- Hide Advanced Filters</span>
                    </button>
                    
                    <div x-show="showFilters" x-collapse class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-900 dark:text-white">Category</label>
                            <select id="category" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stock Status Filter -->
                        <div>
                            <label for="stock_status" class="block text-sm font-medium text-gray-900 dark:text-white">Stock Status</label>
                            <select id="stock_status" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="">All Status</option>
                                <option>In Stock</option>
                                <option>Low Stock</option>
                                <option>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-x-3">
                    <button type="button" class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Preview
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Generate & Download
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reports</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Report Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Generated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Format</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentReports ?? [] as $report)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->date_range }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->created_at }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                {{ $report->format === 'pdf' ? 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30' : 
                                   ($report->format === 'excel' ? 'bg-green-50 text-green-700 ring-green-600/10 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30' : 
                                   'bg-blue-50 text-blue-700 ring-blue-600/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30') }}">
                                {{ strtoupper($report->format) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ $report->download_url }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                Download
                            </a>
                            <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            No reports generated yet. Create your first report above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scheduled Reports -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Scheduled Reports</h3>
            <button type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Schedule Report
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($scheduledReports ?? [] as $schedule)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $schedule->type }} • {{ $schedule->frequency }} • {{ $schedule->format }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $schedule->active ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $schedule->active ? 'Active' : 'Inactive' }}
                        </span>
                        <button type="button" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button type="button" class="p-2 text-red-400 hover:text-red-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No scheduled reports</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by scheduling your first automated report.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection