<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index($tenant)
    {
        // Get the tenant from route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        // Start query
        $query = Supplier::where('tenant_id', $tenantId);

        // Apply search
        if ($search = request('search')) {
            $query->search($search);
        }

        // Apply status filter
        if (request()->has('status') && request('status') !== 'all') {
            $query->where('is_active', request('status') === 'active');
        }

        // Get suppliers with pagination
        $suppliers = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total_suppliers' => Supplier::where('tenant_id', $tenantId)->count(),
            'active_suppliers' => Supplier::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->count(),
            'inactive_suppliers' => Supplier::where('tenant_id', $tenantId)
                ->where('is_active', false)
                ->count(),
        ];

        return view('suppliers.index', compact('suppliers', 'stats', 'tenant'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create($tenant)
    {
        return view('suppliers.create', compact('tenant'));
    }

    /**
     * Store a newly created supplier in storage.
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'payment_terms' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_swift_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Add tenant_id
        $validated['tenant_id'] = $tenantId;
        $validated['is_active'] = $request->has('is_active');

        // Create supplier
        Supplier::create($validated);

        return redirect()
            ->route('company.suppliers.index', $tenant)
            ->with('success', 'Supplier created successfully!');
    }

    /**
     * Display the specified supplier.
     */
    public function show($tenant, Supplier $supplier)
    {
        // Verify supplier belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($supplier->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this supplier.');
        }

        // Load relationships
        $supplier->load(['items', 'purchaseOrders']);

        return view('suppliers.show', compact('supplier', 'tenant'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit($tenant, Supplier $supplier)
    {
        // Verify supplier belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($supplier->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this supplier.');
        }

        return view('suppliers.edit', compact('supplier', 'tenant'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, $tenant, Supplier $supplier)
    {
        // Verify supplier belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($supplier->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this supplier.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'payment_terms' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_swift_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $supplier->update($validated);

        return redirect()
            ->route('company.suppliers.show', [$tenant, $supplier])
            ->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy($tenant, Supplier $supplier)
    {
        // Verify supplier belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($supplier->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this supplier.');
        }

        // Check if supplier has associated items
        if ($supplier->items()->count() > 0) {
            return redirect()
                ->route('company.suppliers.show', [$tenant, $supplier])
                ->with('error', 'Cannot delete supplier with associated items. Please reassign items first.');
        }

        // Check if supplier has associated purchase orders
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()
                ->route('company.suppliers.show', [$tenant, $supplier])
                ->with('error', 'Cannot delete supplier with associated purchase orders.');
        }

        $supplier->delete();

        return redirect()
            ->route('company.suppliers.index', $tenant)
            ->with('success', 'Supplier deleted successfully!');
    }

    /**
     * Toggle supplier status (AJAX).
     */
    public function toggleStatus($tenant, Supplier $supplier)
    {
        // Verify supplier belongs to current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($supplier->tenant_id !== $tenantModel->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $supplier->update(['is_active' => !$supplier->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier status updated.',
            'supplier' => $supplier->fresh()
        ]);
    }

    /**
     * Get suppliers for dropdown (AJAX).
     */
    public function getSuppliers($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $suppliers = Supplier::where('tenant_id', $tenantModel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'company_name', 'email', 'phone']);

        return response()->json($suppliers);
    }
}