<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function inventory($tenant)
    {
        return view('reports.index')->with([
            'title' => 'Inventory Report',
            'tenant' => $tenant,
            'warehouses' => [],
            'categories' => [],
            'recentReports' => [],
            'scheduledReports' => []
        ]);
    }

    public function sales($tenant)
    {
        return view('reports.index')->with([
            'title' => 'Sales Report',
            'tenant' => $tenant,
            'warehouses' => [],
            'categories' => [],
            'recentReports' => [],
            'scheduledReports' => []
        ]);
    }

    public function stockMovements($tenant)
    {
        return view('reports.index')->with([
            'title' => 'Stock Movements Report',
            'tenant' => $tenant,
            'warehouses' => [],
            'categories' => [],
            'recentReports' => [],
            'scheduledReports' => []
        ]);
    }
}