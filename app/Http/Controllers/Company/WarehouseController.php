<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses.
     */
    public function index($tenant)
    {
        $warehouses = Warehouse::all();
        return view('warehouse.index', compact('warehouses', 'tenant'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create($tenant)
    {
        return view('warehouse.create', compact('tenant'));
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant.warehouses', 'code'), // Uses tenant DB
            ],
            'status'          => 'required|in:active,inactive,under_maintenance',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'nullable|string|max:255',
            'postal_code'     => 'nullable|string|max:20',
            'country'         => 'required|string|max:255',
            'storage_capacity'=> 'required|integer|min:0',
            'current_stock'   => 'nullable|integer|min:0',
            'manager_name'    => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:20',
            'contact_email'   => [
                'nullable',
                'email',
                Rule::unique('tenant.warehouses', 'contact_email'), // Uses tenant DB
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('warehouses', 'public');
        }

        Warehouse::create($validated);

        return redirect()
            ->route('company.warehouses.index', $tenant)
            ->with('success', 'Warehouse created successfully!');
    }

    /**
     * Display the specified warehouse.
     */
    public function show($tenant, Warehouse $warehouse)
    {
        return view('warehouse.show', compact('warehouse', 'tenant'));
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit($tenant, Warehouse $warehouse)
    {
        return view('warehouse.edit', compact('warehouse', 'tenant'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, $tenant, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant.warehouses', 'code')->ignore($warehouse->id),
            ],
            'status'          => 'required|in:active,inactive,under_maintenance',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'nullable|string|max:255',
            'postal_code'     => 'nullable|string|max:20',
            'country'         => 'required|string|max:255',
            'storage_capacity'=> 'required|integer|min:0',
            'current_stock'   => 'nullable|integer|min:0',
            'manager_name'    => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:20',
            'contact_email'   => [
                'nullable',
                'email',
                Rule::unique('tenant.warehouses', 'contact_email')->ignore($warehouse->id),
            ],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($warehouse->image) {
                Storage::disk('public')->delete($warehouse->image);
            }
            $validated['image'] = $request->file('image')->store('warehouses', 'public');
        }

        $warehouse->update($validated);

        return redirect()
            ->route('company.warehouses.index', $tenant)
            ->with('success', 'Warehouse updated successfully!');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy($tenant, Warehouse $warehouse)
    {
        if ($warehouse->image) {
            Storage::disk('public')->delete($warehouse->image);
        }

        $warehouse->delete();

        return redirect()
            ->route('company.warehouses.index', $tenant)
            ->with('success', 'Warehouse deleted successfully!');
    }
}