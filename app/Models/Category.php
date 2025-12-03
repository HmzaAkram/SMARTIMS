<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
protected $connection = 'tenant';
    protected $fillable = [
        'name',
        'description',
    ];

    // Relationship with items
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}