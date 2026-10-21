<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'description',
        'image_url',
        'shopee_url',
        'rating',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'integer',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Relationship to Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship to Reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Helper to get formatted price (e.g. Rp 149.000)
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Accessor to ensure image_url is always an absolute working URL.
     * Supports external hotlinks (Unsplash, CDN) and local upload assets.
     */
    public function getImageUrlAttribute(?string $value): string
    {
        if (empty($value)) {
            return asset('images/placeholder.svg');
        }

        // If it contains local upload path (/uploads/...), resolve with asset()
        if (str_contains($value, '/uploads/products/')) {
            $fileName = basename($value);
            return asset('uploads/products/' . $fileName);
        }

        return $value;
    }

    /**
     * Helper to get verified working Shopee URL.
     * Sanitizes messy session query params (like extraParams) to ensure
     * direct canonical link to the specific product on Shopee.
     */
    public function getShopeeUrlAttribute(?string $value): string
    {
        if (empty($value) || str_contains($value, '123456789')) {
            return 'https://shopee.co.id/search?keyword=' . urlencode($this->name);
        }

        // Direct canonical product link if shopid and itemid exist
        if (preg_match('/-i\.(\d+)\.(\d+)/i', $value, $m) || preg_match('/product\/(\d+)\/(\d+)/i', $value, $m)) {
            return "https://shopee.co.id/product/{$m[1]}/{$m[2]}";
        }

        // Preserve search query if it is a search link
        if (str_contains($value, 'search')) {
            return $value;
        }

        // Clean out messy query parameters that can cause 404 or auth errors
        if (str_contains($value, '?')) {
            $parts = explode('?', $value, 2);
            return $parts[0];
        }

        return $value;
    }

    /**
     * Helper to recalculate average rating from reviews
     */
    public function recalculateRating(): void
    {
        $avg = $this->reviews()->avg('rating');
        $this->rating = $avg ? round($avg, 2) : 0;
        $this->save();
    }
}
