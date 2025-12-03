@extends('layouts.company')

@section('title', 'Edit Stock Movement - SmartIMS')

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
                    <span class="ml-4 text-sm font-medium text-gray-900">Edit Movement</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Edit Stock Movement
        </h2>
        <p class="mt-1 text-sm text-gray-500">Update stock movement details</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('company.stock-movements.update', [$tenant, $stockMovement]) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Movement Details</h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Item -->
                    <div class="sm:col-span-2">
                        <label for="item_id" class="block text-sm font-medium text-gray-900">Item *</label>
                        <select name="item_id" id="item_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id', $stockMovement->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }} (SKU: {{ $item->sku }})</option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warehouse -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-900">Warehouse *</label>
                        <select name="warehouse_id" id="warehouse_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $stockMovement->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-900">Type *</label>
                        <select name="type" id="type" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Type</option>
                            <option value="in" {{ old('type', $stockMovement->type) == 'in' ? 'selected' : '' }}>In (Addition)</option>
                            <option value="out" {{ old('type', $stockMovement->type) == 'out' ? 'selected' : '' }}>Out (Removal)</option>
                            <option value="adjustment" {{ old('type', $stockMovement->type) == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="transfer" {{ old('type', $stockMovement->type) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-900">Quantity *</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $stockMovement->quantity) }}" min="1" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        @error('quantity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-900">Unit Price</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $stockMovement->unit_price) }}" step="0.01" class="block w-full rounded-md border-0 py-2 pl-7 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                        @error('unit_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference -->
                    <div class="sm:col-span-2">
                        <label for="reference" class="block text-sm font-medium text-gray-900">Reference</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference', $stockMovement->reference) }}" placeholder="e.g., PO #12345" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        @error('reference')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-900">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">{{ old('notes', $stockMovement->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-x-3">
            <a href="{{ route('company.stock-movements.index', $tenant) }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Update Movement
            </button>
        </div>
    </form>
</div>
@endsection