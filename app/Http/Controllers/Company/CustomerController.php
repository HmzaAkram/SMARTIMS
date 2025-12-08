<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index($tenant)
    {
        // Get the tenant from route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        // Start query
        $query = Customer::where('tenant_id', $tenantId);

        // Apply search
        if ($search = request('search')) {
            $query->search($search);
        }

        // Apply status filter
        if (request()->has('status') && request('status') !== 'all') {
            $query->where('is_active', request('status') === 'active');
        }

        // Apply type filter
        if (request()->has('type') && request('type') !== 'all') {
            $query->where('customer_type', request('type'));
        }

        // Get customers with pagination
        $customers = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total_customers' => Customer::where('tenant_id', $tenantId)->count(),
            'active_customers' => Customer::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->count(),
            'inactive_customers' => Customer::where('tenant_id', $tenantId)
                ->where('is_active', false)
                ->count(),
        ];

        return view('customers.index', compact('customers', 'stats', 'tenant'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        // Generate customer code
        $customerCode = Customer::generateCustomerCode($tenantModel->id);
        
        return view('customers.create', compact('tenant', 'customerCode'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request, $tenant)
    {
        // Get the tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,tenant_id,' . $tenantId,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'customer_type' => 'required|in:retail,wholesale,corporate,government,walkin',
            'credit_limit' => 'nullable|numeric|min:0',
            'opening_balance' => 'nullable|numeric',
            'opening_balance_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Add tenant_id and generate customer code
        $validated['tenant_id'] = $tenantId;
        $validated['customer_code'] = Customer::generateCustomerCode($tenantId);
        $validated['is_active'] = $request->has('is_active');
        $validated['current_balance'] = $request->opening_balance ?? 0;

        // Create customer
        Customer::create($validated);

        return redirect()
            ->route('company.customers.index', $tenant)
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer.
     */
    public function show($tenant, Customer $customer)
    {
        // Verify customer belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($customer->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this customer.');
        }

        // Load relationships
        $customer->load(['sales', 'invoices', 'payments']);

        return view('customers.show', compact('customer', 'tenant'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($tenant, Customer $customer)
    {
        // Verify customer belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($customer->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this customer.');
        }

        return view('customers.edit', compact('customer', 'tenant'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $tenant, Customer $customer)
    {
        // Verify customer belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($customer->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this customer.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id . ',id,tenant_id,' . $tenantModel->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'customer_type' => 'required|in:retail,wholesale,corporate,government,walkin',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $customer->update($validated);

        return redirect()
            ->route('company.customers.show', [$tenant, $customer])
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy($tenant, Customer $customer)
    {
        // Verify customer belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($customer->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this customer.');
        }

        // Check if customer has associated sales
        if ($customer->sales()->count() > 0) {
            return redirect()
                ->route('company.customers.show', [$tenant, $customer])
                ->with('error', 'Cannot delete customer with associated sales. Please reassign sales first.');
        }

        // Check if customer has associated invoices
        if ($customer->invoices()->count() > 0) {
            return redirect()
                ->route('company.customers.show', [$tenant, $customer])
                ->with('error', 'Cannot delete customer with associated invoices.');
        }

        $customer->delete();

        return redirect()
            ->route('company.customers.index', $tenant)
            ->with('success', 'Customer deleted successfully!');
    }

    /**
     * Toggle customer status (AJAX).
     */
    public function toggleStatus($tenant, Customer $customer)
    {
        // Verify customer belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($customer->tenant_id !== $tenantModel->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $customer->update(['is_active' => !$customer->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated.',
            'customer' => $customer->fresh()
        ]);
    }

    /**
     * Get customers for dropdown (AJAX).
     */
    public function getCustomers($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $customers = Customer::where('tenant_id', $tenantModel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'company_name', 'customer_code', 'email', 'phone', 'current_balance']);

        return response()->json($customers);
    }

    /**
     * Get customer details (AJAX).
     */
    public function getCustomerDetails($tenant, $customerId)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $customer = Customer::where('tenant_id', $tenantModel->id)
            ->where('id', $customerId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }
}