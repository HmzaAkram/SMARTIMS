<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'tax_number',
        'website',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'payment_terms',
        'bank_name',
        'bank_account_number',
        'bank_swift_code',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(Order::class)->where('order_type', 'purchase');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->zip_code) $parts[] = $this->zip_code;
        if ($this->country) $parts[] = $this->country;
        
        return implode(', ', $parts);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('company_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}