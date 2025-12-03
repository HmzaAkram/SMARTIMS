<?php
// app/Http/Controllers/Warehouse/WarehouseController.php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('items')->get();
        return view('warehouse.index', compact('warehouses'));
    }
}