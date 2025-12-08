@extends('layouts.company')

@section('title', 'Suppliers - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Suppliers
            </h2>
            <p class="mt-1 text-sm text-gray-500">Manage your suppliers and vendor information</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('company.suppliers.create', $tenant) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New Supplier
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Suppliers</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_suppliers'] }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Active Suppliers</dt>
            <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['active_suppliers'] }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Inactive Suppliers</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-400">{{ $stats['inactive_suppliers'] }}</dd>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="rounded-lg bg-white shadow p-4">
        <form method="GET" class="space-y-4">
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
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search suppliers..." class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <button type="submit" class="w-full rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                        Filter
                    </button>
                    <a href="{{ route('company.suppliers.index', $tenant) }}" class="w-full rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300 text-center">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Suppliers Table -->
    <div class="rounded-lg bg-white shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Terms</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <a href="{{ route('company.suppliers.show', [$tenant, $supplier]) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                        {{ $supplier->name }}
                                    </a>
                                    @if($supplier->company_name)
                                    <div class="text-sm text-gray-500">{{ $supplier->company_name }}</div>
                                    @endif
                                    @if($supplier->tax_number)
                                    <div class="text-xs text-gray-400">Tax: {{ $supplier->tax_number }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($supplier->contact_person)
                            <div class="text-sm font-medium text-gray-900">{{ $supplier->contact_person }}</div>
                            @endif
                            <div class="text-sm text-gray-500">{{ $supplier->email }}</div>
                            <div class="text-sm text-gray-500">{{ $supplier->phone }}</div>
                            @if($supplier->mobile)
                            <div class="text-sm text-gray-500">M: {{ $supplier->mobile }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($supplier->city)
                                {{ $supplier->city }}
                                @if($supplier->state), {{ $supplier->state }}@endif
                                @if($supplier->country), {{ $supplier->country }}@endif
                                @else
                                <span class="text-gray-400">No address</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $supplier->payment_terms ?? 'Not set' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $supplier->status_badge }}">
                                {{ $supplier->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('company.suppliers.show', [$tenant, $supplier]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('company.suppliers.edit', [$tenant, $supplier]) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                            <button onclick="toggleStatus({{ $supplier->id }})" class="text-gray-600 hover:text-gray-900">
                                {{ $supplier->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No suppliers</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new supplier.</p>
                            <div class="mt-6">
                                <a href="{{ route('company.suppliers.create', $tenant) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    New Supplier
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(supplierId) {
    if (confirm('Are you sure you want to change the supplier status?')) {
        fetch(`/company/{{ $tenant }}/suppliers/${supplierId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update status');
        });
    }
}
</script>
@endpush
@endsection