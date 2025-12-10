<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];
    
    public $timestamps = false;
    
    /**
     * Get setting value
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    /**
     * Set setting value
     */
    public static function setValue($key, $value, $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
    
    /**
     * Get all settings by group
     */
    public static function getGroup($group)
    {
        return self::where('group', $group)->pluck('value', 'key')->toArray();
    }
}