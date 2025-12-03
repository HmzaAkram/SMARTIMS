<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'code',
        'status',
        'description',
        'image',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'storage_capacity',
        'current_stock',
        'manager_name',
        'contact_phone',
        'contact_email',
    ];

    protected $casts = [
        'storage_capacity' => 'integer',
        'current_stock' => 'integer',
    ];

    // Relationship with stock movements
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Relationship with items
    public function items()
    {
        return $this->hasManyThrough(Item::class, StockMovement::class, 'warehouse_id', 'id', 'id', 'item_id');
    }
}