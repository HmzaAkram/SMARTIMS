@extends('layouts.company')

@section('title', $supplier->name . ' - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $supplier->name }}
            </h2>
            <div class="mt-1 flex items-center space-x-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $supplier->status_badge }}">
                    {{ $supplier->status_text }}
                </span>
                @if($supplier->company_name)
                <span class="text-sm text-gray-500">{{ $supplier->company_name }}</span>
                @endif
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('company.suppliers.edit', [$tenant, $supplier]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Supplier
            </a>
            <a href="{{ route('company.suppliers.index', $tenant) }}" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                Back to Suppliers
            </a>
        </div>
    </div>

    <!-- Supplier Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($supplier->email)
                                <a href="mailto:{{ $supplier->email }}" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->email }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($supplier->phone)
                                <a href="tel:{{ $supplier->phone }}" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->phone }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($supplier->mobile)
                                <a href="tel:{{ $supplier->mobile }}" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->mobile }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Website</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($supplier->website)
                                <a href="{{ $supplier->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->website }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tax Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->tax_number ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Address Information -->
            @if($supplier->address || $supplier->city)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $supplier->full_address }}</div>
                </div>
            </div>
            @endif

            <!-- Contact Person -->
            @if($supplier->contact_person)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contact Person</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->contact_person }}</dd>
                        </div>
                        
                        @if($supplier->contact_person_phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="tel:{{ $supplier->contact_person_phone }}" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->contact_person_phone }}</a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($supplier->contact_person_email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $supplier->contact_person_email }}" class="text-indigo-600 hover:text-indigo-900">{{ $supplier->contact_person_email }}</a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($supplier->notes)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $supplier->notes }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Payment Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Terms</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->payment_terms ?? 'Not set' }}</dd>
                        </div>
                        
                        @if($supplier->bank_name)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->bank_name }}</dd>
                        </div>
                        @endif
                        
                        @if($supplier->bank_account_number)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->bank_account_number }}</dd>
                        </div>
                        @endif
                        
                        @if($supplier->bank_swift_code)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">SWIFT Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->bank_swift_code }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Supplier Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Supplier Statistics</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Items</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $supplier->items->count() }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Purchase Orders</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $supplier->purchaseOrders->count() }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $supplier->status_badge }}">
                                    {{ $supplier->status_text }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $supplier->created_at->format('M d, Y') }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900">{{ $supplier->updated_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button onclick="toggleStatus()" class="w-full inline-flex items-center justify-center rounded-md bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100">
                            {{ $supplier->is_active ? 'Deactivate Supplier' : 'Activate Supplier' }}
                        </button>
                        
                        <form method="POST" action="{{ route('company.suppliers.destroy', [$tenant, $supplier]) }}" onsubmit="return confirm('Are you sure you want to delete this supplier? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">
                                Delete Supplier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus() {
    if (confirm('Are you sure you want to change the supplier status?')) {
        fetch('{{ route("company.suppliers.index", $tenant) }}/{{ $supplier->id }}/toggle-status', {
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