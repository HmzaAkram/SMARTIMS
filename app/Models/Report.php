<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'name', 'type', 'date_range', 'format', 'file_path',
        'is_scheduled', 'frequency'
    ];
}