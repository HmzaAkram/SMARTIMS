<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'item_id',
        'quantity',
        'min_quantity',
        'max_quantity',
        'location',
        'last_updated',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_quantity' => 'decimal:2',
        'max_quantity' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Accessors
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= $this->min_quantity) {
            return 'low';
        } elseif ($this->quantity >= $this->max_quantity) {
            return 'high';
        }
        return 'normal';
    }

    public function getStockStatusBadgeAttribute()
    {
        return match ($this->stock_status) {
            'low' => 'bg-red-100 text-red-800',
            'high' => 'bg-green-100 text-green-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }
}