<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
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
        'invoice_number',
        'amount',
        'currency',
        'status',
        'payment_method',
        'transaction_id',
        'due_date',
        'paid_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'date',
        'metadata' => 'array',
    ];

    /**
     * Get the tenant that owns the payment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate a new invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ym') . '-';
        
        // Find the last invoice with the same prefix
        $lastInvoice = static::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            // Extract the number part
            $lastNumber = intval(substr($lastInvoice->invoice_number, strlen($prefix)));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return $prefix . $newNumber;
    }

    /**
     * Get the last part of the invoice number.
     */
    public function getInvoiceNumberAttribute($value): string
    {
        return $value ?? self::generateInvoiceNumber();
    }

    /**
     * Generate a random transaction ID.
     */
    public static function generateTransactionId(): string
    {
        return 'txn_' . Str::random(16);
    }
}