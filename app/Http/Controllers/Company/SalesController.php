<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of sales orders.
     */
    public function index($tenant)
    {
        // Get the tenant from the route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        // Start query for sales orders only
        $query = Order::where('tenant_id', $tenantId)
            ->where('order_type', 'sales');

        // Apply filters
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($customer_id = request('customer_id')) {
            $query->where('customer_id', $customer_id);
        }

        if ($date_from = request('date_from')) {
            $query->whereDate('order_date', '>=', $date_from);
        }

        if ($date_to = request('date_to')) {
            $query->whereDate('order_date', '<=', $date_to);
        }

        // Get sales orders with pagination
        $sales = $query->with(['customer', 'warehouse'])
            ->latest()
            ->paginate(20);

        // Get customers for filter dropdown
        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();

        // Calculate sales statistics
        $stats = $this->getSalesStatistics($tenantId);

        return view('sales.index', compact('sales', 'stats', 'customers', 'tenant'));
    }

    /**
     * Show the form for creating a new sales order.
     */
    public function create($tenant)
    {
        // Get the tenant from the route parameter
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        // Get customers (with tenant_id filter)
        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        // Get warehouses (WITHOUT tenant_id filter since column doesn't exist)
        $warehouses = Warehouse::orderBy('name')->get();
        
        // Get items (WITHOUT tenant_id filter since column doesn't exist)
        $items = Item::orderBy('name')->get();

        return view('sales.create', compact('customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Store a newly created sales order in storage.
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
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'payment_terms' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create sales order
            $order = new Order();
            $order->tenant_id = $tenantId;
            $order->customer_id = $validated['customer_id'];
            $order->warehouse_id = $validated['warehouse_id'];
            $order->order_type = 'sales';
            $order->order_date = $validated['order_date'];
            $order->delivery_date = $validated['delivery_date'] ?? null;
            $order->order_number = $this->generateSalesOrderNumber($tenantId);
            $order->status = 'pending';
            $order->notes = $validated['notes'] ?? null;
            $order->shipping_address = $validated['shipping_address'] ?? null;
            $order->billing_address = $validated['billing_address'] ?? null;

            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $orderItemsData = [];
            
            foreach ($validated['items'] as $item) {
                $itemQuantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $itemDiscount = $item['discount'] ?? 0;
                $taxRate = $item['tax_rate'] ?? 0;
                
                $itemTotal = $itemQuantity * $unitPrice;
                $itemDiscountAmount = $itemDiscount;
                $itemTaxAmount = ($itemTotal - $itemDiscountAmount) * ($taxRate / 100);
                $itemNetTotal = $itemTotal - $itemDiscountAmount + $itemTaxAmount;
                
                $subtotal += $itemNetTotal;
                $totalTax += $itemTaxAmount;
                
                $orderItemsData[] = [
                    'item_id' => $item['item_id'],
                    'quantity' => $itemQuantity,
                    'unit_price' => $unitPrice,
                    'discount' => $itemDiscountAmount,
                    'tax' => $itemTaxAmount,
                    'total' => $itemNetTotal,
                ];
            }

            $order->subtotal = $subtotal;
            $order->discount = $validated['discount'] ?? 0;
            $order->tax = $validated['tax'] ?? $totalTax;
            $order->shipping_cost = $validated['shipping_cost'] ?? 0;
            $order->total_amount = $subtotal - $order->discount + $order->tax + $order->shipping_cost;

            // Save order
            $order->save();

            // Save order items
            foreach ($orderItemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount' => $itemData['discount'],
                    'tax' => $itemData['tax'],
                    'total' => $itemData['total'],
                ]);
            }

            // Create payment record if payment method is specified
            if (!empty($validated['payment_method'])) {
                Payment::create([
                    'tenant_id' => $tenantId,
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'payment_method' => $validated['payment_method'],
                    'payment_terms' => $validated['payment_terms'] ?? null,
                    'status' => 'pending',
                    'due_date' => $validated['delivery_date'] ?? Carbon::parse($validated['order_date'])->addDays(30),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('company.sales.show', [$tenant, $order])
                ->with('success', 'Sales order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create sales order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sales order.
     */
    public function show($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            abort(403, 'Unauthorized access to this sales order.');
        }

        // Load relationships
        $order->load([
            'items.item',
            'customer',
            'warehouse',
            'payments'
        ]);

        return view('sales.show', compact('order', 'tenant'));
    }

    /**
     * Show the form for editing the specified sales order.
     */
    public function edit($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            abort(403, 'Unauthorized access to this sales order.');
        }

        // Only allow editing of pending/confirmed orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()
                ->route('company.sales.show', [$tenant, $order])
                ->with('error', 'Cannot edit a delivered or cancelled sales order.');
        }

        $tenantId = $tenantModel->id;

        // Get customers (with tenant_id filter)
        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        // Get warehouses (WITHOUT tenant_id filter)
        $warehouses = Warehouse::orderBy('name')->get();
        
        // Get items (WITHOUT tenant_id filter)
        $items = Item::orderBy('name')->get();

        $order->load('items');

        return view('sales.edit', compact('order', 'customers', 'warehouses', 'items', 'tenant'));
    }

    /**
     * Update the specified sales order in storage.
     */
    public function update(Request $request, $tenant, Order $order)
    {
        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            abort(403, 'Unauthorized access to this sales order.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'payment_terms' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        $order->update($validated);

        return redirect()
            ->route('company.sales.show', [$tenant, $order])
            ->with('success', 'Sales order updated successfully!');
    }

    /**
     * Remove the specified sales order from storage.
     */
    public function destroy($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            abort(403, 'Unauthorized access to this sales order.');
        }

        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return redirect()
                ->route('company.sales.show', [$tenant, $order])
                ->with('error', 'Only pending sales orders can be deleted.');
        }

        DB::beginTransaction();

        try {
            // Delete order items
            $order->items()->delete();
            
            // Delete payments
            $order->payments()->delete();
            
            // Delete the order
            $order->delete();

            DB::commit();

            return redirect()
                ->route('company.sales.index', $tenant)
                ->with('success', 'Sales order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to delete sales order: ' . $e->getMessage());
        }
    }

    /**
     * Print sales invoice.
     */
    public function printInvoice($tenant, Order $order)
    {
        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            abort(403, 'Unauthorized access to this sales order.');
        }

        $order->load(['items.item', 'customer', 'warehouse', 'payments']);
        
        return view('sales.print.invoice', compact('order', 'tenant'));
    }

    /**
     * Update sales order status.
     */
    public function updateStatus(Request $request, $tenant, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        // Verify the order belongs to the current tenant and is a sales order
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        if ($order->tenant_id !== $tenantModel->id || $order->order_type !== 'sales') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        DB::beginTransaction();

        try {
            $order->update(['status' => $validated['status']]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate sales report.
     */
    public function report($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;

        $startDate = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $customerId = request('customer_id');
        $status = request('status');

        $query = Order::where('tenant_id', $tenantId)
            ->where('order_type', 'sales')
            ->whereBetween('order_date', [$startDate, $endDate]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $sales = $query->with(['customer'])
            ->orderBy('order_date')
            ->get();

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();

        $summary = [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total_amount'),
            'average_order_value' => $sales->count() > 0 ? $sales->sum('total_amount') / $sales->count() : 0,
            'pending_orders' => $sales->where('status', 'pending')->count(),
            'completed_orders' => $sales->where('status', 'delivered')->count(),
        ];

        return view('sales.report', compact('sales', 'summary', 'customers', 'tenant', 'startDate', 'endDate'));
    }

    /**
     * Get sales statistics.
     */
    private function getSalesStatistics($tenantId)
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        return [
            'today_sales' => Order::where('tenant_id', $tenantId)
                ->where('order_type', 'sales')
                ->whereDate('order_date', $today)
                ->sum('total_amount') ?? 0,
            
            'monthly_sales' => Order::where('tenant_id', $tenantId)
                ->where('order_type', 'sales')
                ->where('order_date', '>=', $startOfMonth)
                ->sum('total_amount') ?? 0,
            
            'yearly_sales' => Order::where('tenant_id', $tenantId)
                ->where('order_type', 'sales')
                ->where('order_date', '>=', $startOfYear)
                ->sum('total_amount') ?? 0,
            
            'total_customers' => Customer::where('tenant_id', $tenantId)->count(),
            
            'pending_sales' => Order::where('tenant_id', $tenantId)
                ->where('order_type', 'sales')
                ->where('status', 'pending')
                ->count(),
            
            'completed_sales' => Order::where('tenant_id', $tenantId)
                ->where('order_type', 'sales')
                ->where('status', 'delivered')
                ->count(),
        ];
    }

    /**
     * Generate sales order number.
     */
    private function generateSalesOrderNumber($tenantId)
    {
        $latest = Order::where('tenant_id', $tenantId)
            ->where('order_type', 'sales')
            ->latest()
            ->first();
        
        $number = $latest ? (int) substr($latest->order_number, 4) + 1 : 1;
        return 'SALE-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get items for sales order (AJAX).
     */
    public function getItems($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $tenantId = $tenantModel->id;
        $warehouseId = request('warehouse_id');

        $items = Item::orderBy('name')
            ->get()
            ->map(function($item) use ($warehouseId, $tenantId) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'unit_price' => $item->selling_price ?? $item->unit_price,
                    'current_stock' => 0, // Default to 0 since we don't have inventory table
                ];
            });

        return response()->json($items);
    }
}