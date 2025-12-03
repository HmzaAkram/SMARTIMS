@extends('layouts.company')

@section('title', 'Stock Movements - SmartIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Stock Movements
            </h2>
            <p class="mt-1 text-sm text-gray-500">Track all inventory movements across warehouses</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('company.stock-movements.create', $tenant) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Record Movement
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="rounded-lg bg-white shadow p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" placeholder="Search movements, items, or references..." class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="sr-only">Type</label>
                <select id="type" class="block w-full rounded-md border-0 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    <option value="">All Types</option>
                    <option>In</option>
                    <option>Out</option>
                    <option>Adjustment</option>
                    <option>Transfer</option>
                </select>
            </div>

            <!-- Warehouse Filter -->
            <div>
                <label for="warehouse" class="sr-only">Warehouse</label>
                <select id="warehouse" class="block w-full rounded-md border-0 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    <option value="">All Warehouses</option>
                    <!-- Populate with warehouses -->
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex space-x-2">
                <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
                <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
            <div class="text-sm text-gray-500">
                Showing {{ $movements->firstItem() }} to {{ $movements->lastItem() }} of {{ $movements->total() }} results
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="rounded-lg bg-white shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Item</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Warehouse</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Quantity</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Reference</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($movements as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $movement->item->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $movement->warehouse->name ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium 
                                {{ $movement->type == 'in' ? 'bg-green-100 text-green-700' : 
                                   ($movement->type == 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($movement->type) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $movement->quantity }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $movement->reference ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($movement->notes, 50) ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $movement->user->name ?? 'System' }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('company.stock-movements.show', [$tenant, $movement]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('company.stock-movements.edit', [$tenant, $movement]) }}" class="ml-4 text-gray-600 hover:text-gray-900">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-14 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No stock movements</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by recording a new stock movement.</p>
                            <div class="mt-6">
                                <a href="{{ route('company.stock-movements.create', $tenant) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    Record Movement
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($movements->hasPages())
        <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            {{ $movements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection