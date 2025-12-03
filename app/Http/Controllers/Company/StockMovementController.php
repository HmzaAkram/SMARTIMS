<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index($tenant)
    {
        $movements = StockMovement::with(['item', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('warehouse.stock-movements', compact('movements', 'tenant'));
    }

    public function create($tenant)
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        
        return view('warehouse.stock-movement-create', compact('items', 'warehouses', 'tenant'));
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out,adjustment,transfer',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Update stock based on type
        switch ($validated['type']) {
            case 'in':
            case 'adjustment': // Assuming positive adjustment increases stock
                $item->stock += $validated['quantity'];
                break;
            case 'out':
                if ($item->stock < $validated['quantity']) {
                    return back()->with('error', 'Insufficient stock!');
                }
                $item->stock -= $validated['quantity'];
                break;
            case 'transfer':
                // For transfer, stock remains the same overall, but record movement
                break;
        }
        $item->save();

        // Create movement record
        StockMovement::create($validated);

        return redirect()->route('company.stock-movements.index', $tenant)
            ->with('success', 'Stock movement recorded successfully!');
    }

    public function show($tenant, StockMovement $stockMovement)
    {
        $stockMovement->load(['item', 'warehouse']);
        return view('warehouse.stock-movement-show', compact('stockMovement', 'tenant'));
    }

    public function edit($tenant, StockMovement $stockMovement)
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('warehouse.stock-movement-edit', compact('stockMovement', 'items', 'warehouses', 'tenant'));
    }

    public function update(Request $request, $tenant, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out,adjustment,transfer',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Revert old stock change
        $oldItem = Item::findOrFail($stockMovement->item_id);
        switch ($stockMovement->type) {
            case 'in':
            case 'adjustment':
                $oldItem->stock -= $stockMovement->quantity;
                break;
            case 'out':
                $oldItem->stock += $stockMovement->quantity;
                break;
        }
        $oldItem->save();

        // Apply new stock change
        $newItem = Item::findOrFail($validated['item_id']);
        switch ($validated['type']) {
            case 'in':
            case 'adjustment':
                $newItem->stock += $validated['quantity'];
                break;
            case 'out':
                if ($newItem->stock < $validated['quantity']) {
                    return back()->with('error', 'Insufficient stock!');
                }
                $newItem->stock -= $validated['quantity'];
                break;
        }
        $newItem->save();

        $stockMovement->update($validated);

        return redirect()->route('company.stock-movements.index', $tenant)
            ->with('success', 'Stock movement updated successfully!');
    }

    public function destroy($tenant, StockMovement $stockMovement)
    {
        // Revert stock change before delete
        $item = Item::findOrFail($stockMovement->item_id);
        switch ($stockMovement->type) {
            case 'in':
            case 'adjustment':
                $item->stock -= $stockMovement->quantity;
                break;
            case 'out':
                $item->stock += $stockMovement->quantity;
                break;
        }
        $item->save();

        $stockMovement->delete();

        return redirect()->route('company.stock-movements.index', $tenant)
            ->with('success', 'Stock movement deleted successfully!');
    }
}