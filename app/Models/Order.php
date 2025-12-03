<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'tenant_id',
        'customer_id',
        'warehouse_id',
        'order_type',
        'status',
        'order_date',
        'delivery_date',
        'subtotal',
        'tax',
        'discount',
        'shipping_cost',
        'total_amount',
        'notes',
        'shipping_address',
        'billing_address',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors for badges
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-indigo-100 text-indigo-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match ($this->order_type) {
            'sales' => 'bg-green-100 text-green-800',
            'purchase' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $latest = self::latest()->first();
        $number = $latest ? (int) substr($latest->order_number, 4) + 1 : 1;
        return 'ORD-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}