<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'customer_code',
        'name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'gst_number',
        'pan_number',
        'website',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'customer_type',
        'credit_limit',
        'opening_balance',
        'opening_balance_date',
        'current_balance',
        'payment_terms',
        'bank_name',
        'bank_account_number',
        'bank_ifsc_code',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'opening_balance_date' => 'date',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function getCustomerTypeBadgeAttribute()
    {
        $badges = [
            'retail' => 'bg-blue-100 text-blue-800',
            'wholesale' => 'bg-purple-100 text-purple-800',
            'corporate' => 'bg-indigo-100 text-indigo-800',
            'government' => 'bg-yellow-100 text-yellow-800',
            'walkin' => 'bg-gray-100 text-gray-800',
        ];
        
        return $badges[$this->customer_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getCustomerTypeTextAttribute()
    {
        return ucfirst($this->customer_type);
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
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('customer_code', 'like', "%{$search}%")
              ->orWhere('gst_number', 'like', "%{$search}%");
        });
    }

    // Methods
    public static function generateCustomerCode($tenantId)
    {
        $count = self::where('tenant_id', $tenantId)->count() + 1;
        return 'CUST' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function updateBalance($amount)
    {
        $this->current_balance += $amount;
        $this->save();
    }
}