<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'type',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationship with item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relationship with warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}