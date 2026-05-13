<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Single selectable configuration row contributing to PricingService totals.
 */
class CustomizationOption extends Model
{
    protected $fillable = [
        'product_id',
        'option_group',
        'value_key',
        'label',
        'price_adjustment',
        'meta',
        'is_default',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
            'meta' => 'array',
            'is_default' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
