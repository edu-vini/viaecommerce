<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'title',
        'slug',
        'sku',
        'brand',
        'badge',
        'price',
        'original_price',
        'cost_price',
        'stock',
        'is_featured',
        'is_best_seller',
        'is_new',
        'is_deal',
        'is_active',
        'rating',
        'reviews_count',
        'model_info',
        'summary',
        'description',
        'images',
        'colors',
        'specifications',
        'measurement_table',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new' => 'boolean',
        'is_deal' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'images' => 'array',
        'colors' => 'array',
        'specifications' => 'array',
        'measurement_table' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        if (is_array($this->images) && count($this->images) > 0) {
            $img = $this->images[0];
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }

            return asset('storage/'.$img);
        }

        return 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=700&q=75';
    }

    public function getFormattedImagesAttribute(): array
    {
        if (! is_array($this->images)) {
            return [];
        }

        return array_map(function ($img) {
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }

            return asset('storage/'.$img);
        }, $this->images);
    }
}
