<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductVariantImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'product_variant_id',
        'image',
    ];

    /**
     * Get the variant that owns the image.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Record activity in history log
     */

}
