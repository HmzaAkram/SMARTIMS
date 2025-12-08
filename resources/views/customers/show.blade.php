@extends('layouts.company')

@section('title', $customer->name . ' - SMARTIMS')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $customer->name }}
            </h2>
            <div class="mt-1 flex items-center space-x-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $customer->status_badge }}">
                    {{ $customer->status_text }}
                </span>
                <span class="text-sm font-medium text-gray-900">{{ $customer->customer_code }}</span>
                @if($customer->company_name)
                <span class="text-sm text-gray-500">{{ $customer->company_name }}</span>
                @endif
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('company.customers.edit', [$tenant, $customer]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2v5m1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Customer
            </a>
            <a href="{{ route('company.customers.index', $tenant) }}" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                Back to Customers
            </a>
        </div>
    </div>

    <!-- Customer Details -->
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
                                @if($customer->email)
                                <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->email }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($customer->phone)
                                <a href="tel:{{ $customer->phone }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->phone }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($customer->mobile)
                                <a href="tel:{{ $customer->mobile }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->mobile }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Website</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($customer->website)
                                <a href="{{ $customer->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $customer->website }}</a>
                                @else
                                <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">GST Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->gst_number ?? 'Not provided' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">PAN Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->pan_number ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Address Information -->
            @if($customer->address || $customer->city)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $customer->full_address }}</div>
                </div>
            </div>
            @endif

            <!-- Contact Person -->
            @if($customer->contact_person)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contact Person</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->contact_person }}</dd>
                        </div>
                        
                        @if($customer->contact_person_phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="tel:{{ $customer->contact_person_phone }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->contact_person_phone }}</a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($customer->contact_person_email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $customer->contact_person_email }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->contact_person_email }}</a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($customer->notes)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $customer->notes }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Financial Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Financial Information</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Current Balance</dt>
                            <dd class="text-sm font-medium {{ $customer->current_balance < 0 ? 'text-green-600' : ($customer->current_balance > 0 ? 'text-red-600' : 'text-gray-900') }}">
                                ₹ {{ number_format($customer->current_balance, 2) }}
                            </dd>
                        </div>
                        
                        @if($customer->credit_limit)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Credit Limit</dt>
                            <dd class="text-sm text-gray-900">₹ {{ number_format($customer->credit_limit, 2) }}</dd>
                        </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Opening Balance</dt>
                            <dd class="text-sm text-gray-900">₹ {{ number_format($customer->opening_balance, 2) }}</dd>
                        </div>
                        
                        @if($customer->opening_balance_date)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Opening Balance Date</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->opening_balance_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Payment Terms</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->payment_terms ?? 'Not set' }}</dd>
                        </div>
                        
                        @if($customer->bank_name)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->bank_name }}</dd>
                        </div>
                        @endif
                        
                        @if($customer->bank_account_number)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->bank_account_number }}</dd>
                        </div>
                        @endif
                        
                        @if($customer->bank_ifsc_code)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">IFSC Code</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->bank_ifsc_code }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Customer Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Customer Statistics</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Customer Type</dt>
                            <dd>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $customer->customer_type_badge }}">
                                    {{ $customer->customer_type_text }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Sales</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $customer->sales->count() }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Invoices</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $customer->invoices->count() }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $customer->status_badge }}">
                                    {{ $customer->status_text }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->updated_at->format('M d, Y') }}</dd>
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
                            {{ $customer->is_active ? 'Deactivate Customer' : 'Activate Customer' }}
                        </button>
                        
                        <form method="POST" action="{{ route('company.customers.destroy', [$tenant, $customer]) }}" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">
                                Delete Customer
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
    if (confirm('Are you sure you want to change the customer status?')) {
        fetch('{{ route("company.customers.index", $tenant) }}/{{ $customer->id }}/toggle-status', {
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