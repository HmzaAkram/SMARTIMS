@extends('layouts.company')

@section('title', 'Sales Report - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Sales Report
            </h2>
            <p class="mt-1 text-sm text-gray-500">Analyze your sales performance and generate reports</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('company.sales.index', $tenant) }}" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales
            </a>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Generate Report
                </button>
                <a href="{{ route('company.sales.report', $tenant) }}" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                    Reset
                </a>
                <button type="button" onclick="printReport()" class="rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                    Print Report
                </button>
            </div>
        </form>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="bg-white shadow rounded-lg p-6">
            <dt class="text-sm font-medium text-gray-500">Total Sales</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $summary['total_sales'] }}</dd>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
            <dd class="mt-1 text-3xl font-semibold text-green-600">${{ number_format($summary['total_revenue'], 2) }}</dd>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <dt class="text-sm font-medium text-gray-500">Average Order Value</dt>
            <dd class="mt-1 text-3xl font-semibold text-blue-600">${{ number_format($summary['average_order_value'], 2) }}</dd>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <dt class="text-sm font-medium text-gray-500">Completed Orders</dt>
            <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $summary['completed_orders'] }}</dd>
        </div>
    </div>

    <!-- Sales Report Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Sales Report: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('company.sales.show', [$tenant, $sale]) }}" class="text-sm font-mono text-indigo-600 hover:text-indigo-900">
                                {{ $sale->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sale->order_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $sale->customer->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $sale->status_badge }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sale->items->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($sale->subtotal, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($sale->tax, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($sale->discount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($sale->total_amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No sales data found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($sales->isNotEmpty())
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Totals:</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            ${{ number_format($sales->sum('subtotal'), 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            ${{ number_format($sales->sum('tax'), 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            ${{ number_format($sales->sum('discount'), 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            ${{ number_format($sales->sum('total_amount'), 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        <!-- Export Options -->
        @if($sales->isNotEmpty())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="exportToCSV()" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                    Export to CSV
                </button>
                <button type="button" onclick="exportToPDF()" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    Export to PDF
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function printReport() {
        window.print();
    }
    
    function exportToCSV() {
        // This would typically make an AJAX call to generate CSV
        alert('CSV export feature would be implemented here');
    }
    
    function exportToPDF() {
        // This would typically make an AJAX call to generate PDF
        alert('PDF export feature would be implemented here');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Set default dates if not set
        if (!document.getElementById('start_date').value) {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            document.getElementById('start_date').valueAsDate = firstDay;
        }
        
        if (!document.getElementById('end_date').value) {
            const today = new Date();
            document.getElementById('end_date').valueAsDate = today;
        }
    });
</script>
@endpush

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white, .bg-white * {
            visibility: visible;
        }
        .bg-white {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush
@endsection