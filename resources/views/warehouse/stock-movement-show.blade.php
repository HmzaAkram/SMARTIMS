@extends('layouts.company')

@section('title', 'Stock Movement #' . $stockMovement->id . ' - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('company.dashboard', $tenant) }}" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('company.stock-movements.index', $tenant) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Stock Movements</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-900">Movement #{{ $stockMovement->id }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Stock Movement #{{ $stockMovement->id }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">{{ ucfirst($stockMovement->type) }} of {{ $stockMovement->quantity }} units</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('company.stock-movements.edit', [$tenant, $stockMovement]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form method="POST" action="{{ route('company.stock-movements.destroy', [$tenant, $stockMovement]) }}" onsubmit="return confirm('Are you sure you want to delete this movement? This will revert the stock change.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Movement Details -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Movement Details</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm">
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium 
                            {{ $stockMovement->type == 'in' ? 'bg-green-100 text-green-700' : 
                               ($stockMovement->type == 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($stockMovement->type) }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Item</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->item->name }} (SKU: {{ $stockMovement->item->sku }})</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Warehouse</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->warehouse->name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $stockMovement->quantity }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit Price</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($stockMovement->unit_price ?? 0, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Reference</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->reference ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->notes ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Recorded By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->user->name ?? 'System' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection