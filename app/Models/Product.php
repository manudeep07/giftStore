<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Customizable catalog SKU with dynamic option-driven pricing.
 *
 * @property-read Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductImage> $images
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CustomizationOption> $customizationOptions
 */
class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'stock',
        'is_featured',
        'is_active',
        'badge_label',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<ProductImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /** @return HasMany<CustomizationOption, $this> */
    public function customizationOptions(): HasMany
    {
        return $this->hasMany(CustomizationOption::class)->orderBy('sort_order');
    }

    /** @return HasMany<Review, $this> */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    /**
     * Public URL for a gallery path (supports remote demo URLs in seeds).
     */
    public function imageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}
