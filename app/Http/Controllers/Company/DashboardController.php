<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index($tenant)
    {
        // Stats for dashboard cards
        $stats = [
            'total_items' => Item::count(),
            // Fixed column names: quantity + reorder_level + unit_price
            'low_stock'   => Item::whereColumn('quantity', '<', 'reorder_level')->count(),
            'value'       => Item::sum(DB::raw('quantity * unit_price')) ?? 0,
        ];

        // Low stock alerts - items below reorder level
        $lowStockAlerts = Item::whereColumn('quantity', '<', 'reorder_level')
            ->select(
                'id',
                'name',
                'sku',
                'quantity',               // real column (no alias needed)
                'reorder_level',          // real column
                'image'
            )
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        // Recent stock movements
        $recentMovements = StockMovement::with(['item:id,name'])
            ->select('id', 'item_id', 'warehouse_id', 'type', 'quantity', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Total warehouses
        $warehouses = Warehouse::count();

        // Chart data for last 7 days
        $chartLabels   = [];
        $stockInData   = [];
        $stockOutData  = [];

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

        // Category distribution for pie chart
        $categoryData        = Category::withCount('items')->get();
        $categories          = $categoryData->pluck('name')->toArray();
        $categoryDistribution = $categoryData->pluck('items_count')->toArray();

        // Handle empty data
        if (empty($categories)) {
            $categories          = ['Uncategorized'];
            $categoryDistribution = [Item::count()];
        }

        return view('company.dashboard', compact(
            'stats',
            'lowStockAlerts',
            'recentMovements',
            'warehouses',
            'chartLabels',
            'stockInData',
            'stockOutData',
            'categories',
            'categoryDistribution'
        ));
    }

    public function settings($tenant)
    {
        return view('company.settings');
    }
}