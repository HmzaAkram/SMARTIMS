<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql'; // Central database connection

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'plan_name',
        'price',
        'status',
        'billing_cycle',
        'trial_ends_at',
        'ends_at',
        'features',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'trial_ends_at' => 'date',
        'ends_at' => 'date',
        'features' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the subscription.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include trialing subscriptions.
     */
    public function scopeTrialing($query)
    {
        return $query->where('status', 'trialing');
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && (!$this->ends_at || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is in trial period.
     */
    public function isTrialing(): bool
    {
        return $this->status === 'trialing' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Get the plan features.
     */
    public function getFeaturesAttribute($value): array
    {
        $defaultFeatures = $this->getDefaultFeatures($this->plan_name);
        
        if ($value) {
            $customFeatures = json_decode($value, true);
            return array_merge($defaultFeatures, $customFeatures ?? []);
        }
        
        return $defaultFeatures;
    }

    /**
     * Get default features for a plan.
     */
    protected function getDefaultFeatures(string $plan): array
    {
        $features = [
            'starter' => [
                'users' => 10,
                'storage' => '5GB',
                'support' => 'basic',
                'api_access' => false,
            ],
            'growth' => [
                'users' => 50,
                'storage' => '50GB',
                'support' => 'priority',
                'api_access' => true,
            ],
            'premium' => [
                'users' => 200,
                'storage' => '200GB',
                'support' => '24/7',
                'api_access' => true,
            ],
            'enterprise' => [
                'users' => 'unlimited',
                'storage' => 'unlimited',
                'support' => 'dedicated',
                'api_access' => true,
            ],
        ];

        return $features[$plan] ?? $features['starter'];
    }
}