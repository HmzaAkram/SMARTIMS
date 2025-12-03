<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tenant)
    {
        $queries = Order::where('tenant_id', Auth::user()->tenant_id);

        // Apply filters
        if ($status = request('status')) {
            $queries->where('status', $status);
        }

        if ($order_type = request('order_type')) {
            $queries->where('order_type', $order_type);
        }

        if ($date_from = request('date_from')) {
            $queries->whereDate('order_date', '>=', $date_from);
        }

        $orders = $queries->with(['customer'])->latest()->paginate(15);

        $stats = [
            'total_orders' => Order::where('tenant_id', Auth::user()->tenant_id)->count(),
            'pending_orders' => Order::where('tenant_id', Auth::user()->tenant_id)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('tenant_id', Auth::user()->tenant_id)->where('status', 'delivered')->count(),
            'total_revenue' => Order::where('tenant_id', Auth::user()->tenant_id)->sum('total_amount'),
        ];

        return view('orders.index', compact('orders', 'stats', 'tenant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tenant)
    {
        $customers = Customer::where('tenant_id', Auth::user()->tenant_id)->get();
        $warehouses = Warehouse::where('tenant_id', Auth::user()->tenant_id)->get();
        $items = Item::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('orders.create', compact('customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_type' => 'required|in:purchase,sales',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        $order = new Order($validated);
        $order->tenant_id = Auth::user()->tenant_id;
        $order->order_number = Order::generateOrderNumber();
        $order->status = 'pending';

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $orderItem) {
            $subtotal += $orderItem['quantity'] * $orderItem['unit_price'];
        }
        $order->subtotal = $subtotal;
        $order->discount = $validated['discount'] ?? 0;
        $order->tax = $validated['tax'] ?? 0;
        $order->shipping_cost = $validated['shipping_cost'] ?? 0;
        $order->total_amount = $subtotal - $order->discount + $order->tax + $order->shipping_cost;

        $order->save();

        // Add items
        foreach ($validated['items'] as $orderItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $orderItem['item_id'],
                'quantity' => $orderItem['quantity'],
                'unit_price' => $orderItem['unit_price'],
                'discount' => 0, // Add if needed
                'tax' => 0, // Add if needed
                'total' => $orderItem['quantity'] * $orderItem['unit_price'],
            ]);
        }

        return redirect()->route('company.orders.index', $tenant)->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($tenant, Order $order)
    {
        $order->load(['items.item', 'customer', 'warehouse']);

        return view('orders.show', compact('order', 'tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tenant, Order $order)
    {
        $customers = Customer::where('tenant_id', Auth::user()->tenant_id)->get();
        $warehouses = Warehouse::where('tenant_id', Auth::user()->tenant_id)->get();
        $items = Item::where('tenant_id', Auth::user()->tenant_id)->get();
        $order->load('items');

        return view('orders.edit', compact('order', 'customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tenant, Order $order)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,confirmed,processing,shipped,delivered,cancelled',
            // Add other fields if editable
        ]);

        $order->update($validated);

        return redirect()->route('company.orders.show', [$tenant, $order])->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tenant, Order $order)
    {
        $order->delete();

        return redirect()->route('company.orders.index', $tenant)->with('success', 'Order deleted successfully!');
    }
}