<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'sku', 'barcode', 'description',
        'category_id', 'warehouse_id', 'unit',
        'unit_price', 'quantity', 'reorder_level',
        'expiry_date', 'batch_number', 'is_active', 'image'
    ];

    protected $casts = [
        'unit_price'    => 'decimal:2',
        'quantity'      => 'integer',
        'reorder_level' => 'integer',
        'expiry_date'   => 'date',
        'is_active'     => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}