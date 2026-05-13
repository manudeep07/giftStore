<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Checkout coupon with fixed or percentage discounts. */
class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'uses_count',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'max_uses' => 'integer',
            'uses_count' => 'integer',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasUsesRemaining(): bool
    {
        if ($this->max_uses === null) {
            return true;
        }

        return $this->uses_count < $this->max_uses;
    }

    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired() && $this->hasUsesRemaining();
    }
}
