<?php
// app/Models/Tenant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = ['name', 'domain', 'database', 'email', 'phone', 'slug'];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $base = Str::slug($tenant->name);
            $domainBase = $tenant->domain ?? $base;

            // Unique slug
            $slug = $base;
            $i = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $tenant->slug = $slug;

            // Unique domain
            $domain = $domainBase;
            $i = 1;
            while (static::where('domain', $domain)->exists()) {
                $domain = explode('.', $domainBase)[0] . '-' . $i++ . '.' . implode('.', array_slice(explode('.', $domainBase), 1));
            }
            $tenant->domain = $domain;

            // Database name
            $tenant->database = 'smartims_' . $tenant->slug;

            // Create the actual database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}