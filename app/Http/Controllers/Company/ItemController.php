<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index($tenant)
    {
        $items = Item::with('category')->paginate(15);
        return view('inventory.items.index', compact('items', 'tenant'));
    }

    public function create($tenant)
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        return view('inventory.items.create', compact('categories', 'warehouses', 'tenant'));
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'sku'           => ['required', 'string', Rule::unique('tenant.items', 'sku')],
            'barcode'       => ['nullable', 'string', Rule::unique('tenant.items', 'barcode')],
            'description'   => 'nullable|string',
            'category_id'   => 'required|exists:tenant.categories,id',
            'unit'          => 'required|string',
            'unit_price'    => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'warehouse_id'  => 'required|exists:tenant.warehouses,id',
            'expiry_date'   => 'nullable|date',
            'batch_number'  => 'nullable|string',
            'is_active'     => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Item::create($validated);

        return redirect()
            ->route('company.items.index', $tenant)
            ->with('success', 'Item created successfully!');
    }

    public function show($tenant, Item $item)
    {
        $item->load('category');
        return view('inventory.items.show', compact('item', 'tenant'));
    }

    public function edit($tenant, Item $item)
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        return view('inventory.items.edit', compact('item', 'categories', 'warehouses', 'tenant'));
    }

    public function update(Request $request, $tenant, Item $item)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'sku'           => ['required', 'string', Rule::unique('tenant.items', 'sku')->ignore($item->id)],
            'barcode'       => ['nullable', 'string', Rule::unique('tenant.items', 'barcode')->ignore($item->id)],
            'description'   => 'nullable|string',
            'category_id'   => 'required|exists:tenant.categories,id',
            'unit'          => 'required|string',
            'unit_price'    => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'warehouse_id'  => 'required|exists:tenant.warehouses,id',
            'expiry_date'   => 'nullable|date',
            'batch_number'  => 'nullable|string',
            'is_active'     => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $item->update($validated);

        return redirect()
            ->route('company.items.index', $tenant)
            ->with('success', 'Item updated successfully!');
    }

    public function destroy($tenant, Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('company.items.index', $tenant)
            ->with('success', 'Item deleted successfully!');
    }
}