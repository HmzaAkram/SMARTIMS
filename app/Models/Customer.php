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
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'company_name',
        'tax_id',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}