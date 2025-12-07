<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tenant)
    {
        // Get the tenant from the route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        // Start query with tenant filter
        $query = Order::where('tenant_id', $tenantId);

        // Apply filters
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($order_type = request('order_type')) {
            $query->where('order_type', $order_type);
        }

        if ($date_from = request('date_from')) {
            $query->whereDate('order_date', '>=', $date_from);
        }

        // Get orders with pagination
        $orders = $query->with(['customer'])
            ->latest()
            ->paginate(15);

        // Calculate stats
        $stats = [
            'total_orders' => Order::where('tenant_id', $tenantId)->count(),
            'pending_orders' => Order::where('tenant_id', $tenantId)
                ->where('status', 'pending')
                ->count(),
            'completed_orders' => Order::where('tenant_id', $tenantId)
                ->where('status', 'delivered')
                ->count(),
            'total_revenue' => Order::where('tenant_id', $tenantId)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
        ];

        return view('orders.index', compact('orders', 'stats', 'tenant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tenant)
    {
        // Get the tenant from the route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        $warehouses = Warehouse::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        $items = Item::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $tenant)
    {
        // Get the tenant from the route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_type' => 'required|in:sales,purchase',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        // Create order
        $order = new Order();
        $order->tenant_id = $tenantId;
        $order->customer_id = $validated['customer_id'];
        $order->warehouse_id = $validated['warehouse_id'];
        $order->order_type = $validated['order_type'];
        $order->order_date = $validated['order_date'];
        $order->delivery_date = $validated['delivery_date'] ?? null;
        $order->order_number = Order::generateOrderNumber();
        $order->status = 'pending';
        $order->notes = $validated['notes'] ?? null;
        $order->shipping_address = $validated['shipping_address'] ?? null;
        $order->billing_address = $validated['billing_address'] ?? null;

        // Calculate totals
        $subtotal = 0;
        $orderItemsData = [];
        
        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $item['discount'] ?? 0;
            $itemTax = $item['tax'] ?? 0;
            $itemNetTotal = $itemTotal - $itemDiscount + $itemTax;
            
            $subtotal += $itemNetTotal;
            
            $orderItemsData[] = [
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $itemDiscount,
                'tax' => $itemTax,
                'total' => $itemNetTotal,
            ];
        }

        $order->subtotal = $subtotal;
        $order->discount = $validated['discount'] ?? 0;
        $order->tax = $validated['tax'] ?? 0;
        $order->shipping_cost = $validated['shipping_cost'] ?? 0;
        $order->total_amount = $subtotal - $order->discount + $order->tax + $order->shipping_cost;

        // Save order
        $order->save();

        // Save order items
        foreach ($orderItemsData as $itemData) {
            $orderItem = new OrderItem($itemData);
            $orderItem->order_id = $order->id;
            $orderItem->save();
        }

        return redirect()
            ->route('company.orders.show', [$tenant, $order])
            ->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load relationships
        $order->load([
            'items.item',
            'customer',
            'warehouse'
        ]);

        return view('orders.show', compact('order', 'tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow editing of pending/confirmed orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()
                ->route('company.orders.show', [$tenant, $order])
                ->with('error', 'Cannot edit a delivered or cancelled order.');
        }

        $tenantId = $tenantModel->id;

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        $warehouses = Warehouse::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        $items = Item::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $order->load('items');

        return view('orders.edit', compact('order', 'customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tenant, Order $order)
    {
        // Verify the order belongs to the current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        // Update order
        $order->update($validated);

        // If status changed to cancelled or delivered, trigger appropriate events
        if ($order->wasChanged('status')) {
            // You can add status change logic here
            // Example: Send notifications, update inventory, etc.
        }

        return redirect()
            ->route('company.orders.show', [$tenant, $order])
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
 * Remove the specified resource from storage.
 */
public function destroy($tenant, Order $order)
{
    // Verify the order belongs to the current tenant
    $tenantModel = Tenant::where('domain', $tenant)
        ->orWhere('slug', $tenant)
        ->firstOrFail();
    
    if ($order->tenant_id !== $tenantModel->id) {
        abort(403, 'Unauthorized access to this order.');
    }

    // Only allow deletion of pending orders
    if ($order->status !== 'pending') {
        return redirect()
            ->route('company.orders.show', [$tenant, $order])
            ->with('error', 'Only pending orders can be deleted.');
    }

    // Delete order items first
    $order->items()->delete();
    
    // Force delete the order (since we removed soft deletes)
    $order->forceDelete();

    return redirect()
        ->route('company.orders.index', $tenant)
        ->with('success', 'Order deleted successfully!');
}

    /**
     * Update order items (AJAX)
     */
    public function updateItems(Request $request, $tenant, Order $order)
    {
        // Verify the order belongs to the current tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow updating items for pending/confirmed orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return response()->json([
                'error' => 'Cannot update items for a delivered or cancelled order.'
            ], 403);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
        ]);

        $subtotal = 0;

        // Update order items
        foreach ($validated['items'] as $itemData) {
            $orderItem = OrderItem::find($itemData['id']);
            
            // Verify the order item belongs to this order
            if ($orderItem->order_id !== $order->id) {
                continue;
            }

            $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
            $itemDiscount = $itemData['discount'] ?? 0;
            $itemTax = $itemData['tax'] ?? 0;
            $itemNetTotal = $itemTotal - $itemDiscount + $itemTax;

            $orderItem->update([
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'discount' => $itemDiscount,
                'tax' => $itemTax,
                'total' => $itemNetTotal,
            ]);

            $subtotal += $itemNetTotal;
        }

        // Recalculate order totals
        $order->subtotal = $subtotal;
        $order->total_amount = $subtotal - $order->discount + $order->tax + $order->shipping_cost;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order items updated successfully.',
            'order' => $order->fresh()
        ]);
    }
}