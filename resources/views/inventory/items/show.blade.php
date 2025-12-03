@extends('layouts.company')

@section('title', 'View Item - SmartIMS')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('company.dashboard', $tenant) }}" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                              clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('company.items.index', $tenant) }}"
                       class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Inventory</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-900">View Item</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $item->name }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">SKU: <span class="font-mono">{{ $item->sku }}</span></p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-2">
            <a href="{{ route('company.items.edit', [$tenant, $item]) }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('company.items.index', $tenant) }}"
               class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Back to List
            </a>
        </div>
    </div>

    {{-- Item Details Card --}}
    <div class="rounded-lg bg-white shadow overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                {{-- Image --}}
                <div class="lg:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Image</dt>
                    <dd class="mt-1">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" alt="{{ $item->name }}"
                                 class="h-48 w-full rounded-lg object-cover shadow-sm">
                        @else
                            <div class="flex h-48 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                No image
                            </div>
                        @endif
                    </dd>
                </div>

                {{-- Basic Info --}}
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $item->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-sm font-mono text-gray-900">{{ $item->sku }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                        <dd class="mt-1 text-sm font-mono text-gray-900">
                            {{ $item->barcode ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $item->category->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit of Measure</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ ucfirst($item->unit ?? '—') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Warehouse</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $item->warehouse->name ?? '—' }}
                        </dd>
                    </div>
                </div>

                {{-- Pricing & Stock --}}
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            ${{ number_format($item->unit_price, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ $item->quantity }}</span>
                            <span class="text-gray-500"> {{ $item->unit }}</span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reorder Level</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $item->reorder_level }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($item->quantity > $item->reorder_level)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                    In Stock
                                </span>
                            @elseif($item->quantity > 0)
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                    Out of Stock
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Active</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $item->is_active ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </div>

                {{-- Additional Details (full width on small screens) --}}
                <div class="md:col-span-2 lg:col-span-3 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $item->description ?? '—' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $item->expiry_date ? $item->expiry_date->format('d M Y') : '—' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Batch Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $item->batch_number ?? '—' }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button (bottom) --}}
    <div class="flex justify-end">
        <a href="{{ route('company.items.index', $tenant) }}"
           class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Back to Inventory
        </a>
    </div>
</div>
@endsection