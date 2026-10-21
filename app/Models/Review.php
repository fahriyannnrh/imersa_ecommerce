<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'rating',
        'review_text',
        'avatar_url',
        'photo_url',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            if ($review->product) {
                $review->product->recalculateRating();
            }
        });

        static::deleted(function ($review) {
            if ($review->product) {
                $review->product->recalculateRating();
            }
        });
    }

    /**
     * Relationship to Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
