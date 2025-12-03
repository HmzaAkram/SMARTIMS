<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index($tenant)
    {
        $items = Item::with('category')->paginate(15);
        return view('inventory.items.index', compact('items'));
    }

    public function create($tenant)
    {
        $categories = Category::all();
        return view('inventory.items.create', compact('categories'));
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);

        return redirect()->route('company.items.index', $tenant)
            ->with('success', 'Item created successfully!');
    }

    public function show($tenant, Item $item)
    {
        return view('inventory.items.show', compact('item'));
    }

    public function edit($tenant, Item $item)
    {
        $categories = Category::all();
        return view('inventory.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $tenant, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku,' . $item->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('company.items.index', $tenant)
            ->with('success', 'Item updated successfully!');
    }

    public function destroy($tenant, Item $item)
    {
        $item->delete();
        return redirect()->route('company.items.index', $tenant)
            ->with('success', 'Item deleted successfully!');
    }
}