<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($tenant)
    {
        // Total Items Count
        $stats['total_items'] = Item::count();
        
        // Low Stock Items (where stock is less than reorder_level)
        $stats['low_stock'] = Item::whereColumn('stock', '<', 'reorder_level')->count();
        
        // Total Stock Value (stock * unit_price)
        $stats['value'] = Item::sum(DB::raw('stock * unit_price')) ?? 0;
        
        // Calculate previous month stats for percentage changes
        $previousMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();
        
        // Get previous month stats for comparison
        $previousMonthItems = Item::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();
        
        $previousMonthStockValue = Item::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->sum(DB::raw('stock * unit_price')) ?? 0;
        
        // Calculate percentage changes
        $itemGrowthPercentage = $previousMonthItems > 0 
            ? (($stats['total_items'] - $previousMonthItems) / $previousMonthItems) * 100 
            : 0;
        
        $valueGrowthPercentage = $previousMonthStockValue > 0 
            ? (($stats['value'] - $previousMonthStockValue) / $previousMonthStockValue) * 100 
            : 0;
        
        $stats['item_growth'] = round($itemGrowthPercentage, 1);
        $stats['value_growth'] = round($valueGrowthPercentage, 1);

        // Low stock alerts - items below reorder level
        $lowStockAlerts = Item::whereColumn('stock', '<', 'reorder_level')
            ->select(
                'id',
                'name',
                'sku',
                'stock as quantity',
                'reorder_level as min_quantity',
                'image'
            )
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Recent stock movements with warehouse relation
        $recentMovements = StockMovement::with(['item:id,name', 'warehouse:id,name'])
            ->select('id', 'item_id', 'warehouse_id', 'type', 'quantity', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Total warehouses
        $warehouses = Warehouse::count();

        // Total customers and suppliers if models exist
        $totalCustomers = 0;
        $totalSuppliers = 0;
        
        try {
            if (class_exists(Customer::class)) {
                $totalCustomers = Customer::count();
            }
            if (class_exists(Supplier::class)) {
                $totalSuppliers = Supplier::count();
            }
        } catch (\Exception $e) {
            // Tables might not exist yet
        }

        // Chart data for last 7 days - FIXED: Use correct column names
        $chartLabels = [];
        $stockInData = [];
        $stockOutData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D');

            $stockInData[] = StockMovement::where('type', 'in')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('quantity');

            $stockOutData[] = StockMovement::where('type', 'out')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('quantity');
        }

        // Stock Status Distribution (In Stock, Low Stock, Out of Stock)
        $totalItemsCount = $stats['total_items'];
        $lowStockCount = $stats['low_stock'];
        $outOfStockCount = Item::where('stock', 0)->count();
        $inStockCount = $totalItemsCount - $lowStockCount - $outOfStockCount;
        
        // Calculate percentages
        $inStockPercentage = $totalItemsCount > 0 ? round(($inStockCount / $totalItemsCount) * 100) : 0;
        $lowStockPercentage = $totalItemsCount > 0 ? round(($lowStockCount / $totalItemsCount) * 100) : 0;
        $outOfStockPercentage = $totalItemsCount > 0 ? round(($outOfStockCount / $totalItemsCount) * 100) : 0;
        
        $stockDistribution = [
            'in_stock' => $inStockPercentage,
            'low_stock' => $lowStockPercentage,
            'out_of_stock' => $outOfStockPercentage,
            'in_stock_count' => $inStockCount,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
        ];

        // Top Categories with item count
        $topCategories = Category::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Orders (if Order model exists)
        $recentOrders = [];
        if (class_exists(Order::class)) {
            $recentOrders = Order::with('customer')
                ->select('id', 'order_number', 'customer_id', 'total_amount', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Sales/Orders statistics for the quick stats
        $todaySales = 0;
        $monthlySales = 0;
        $yearlySales = 0;
        $pendingOrders = 0;
        $completedOrders = 0;
        
        if (class_exists(Order::class)) {
            $todaySales = Order::whereDate('created_at', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $monthlySales = Order::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $yearlySales = Order::whereYear('created_at', Carbon::now()->year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $pendingOrders = Order::where('status', 'pending')->count();
            $completedOrders = Order::where('status', 'delivered')->count();
        }

        return view('company.dashboard', compact(
            'stats',
            'lowStockAlerts',
            'recentMovements',
            'warehouses',
            'chartLabels',
            'stockInData',
            'stockOutData',
            'stockDistribution',
            'topCategories',
            'recentOrders',
            'totalCustomers',
            'totalSuppliers',
            'todaySales',
            'monthlySales',
            'yearlySales',
            'pendingOrders',
            'completedOrders'
        ));
    }

    public function settings($tenant)
    {
        return view('company.settings');
    }
}