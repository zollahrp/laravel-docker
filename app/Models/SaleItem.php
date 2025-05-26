<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SaleItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sale_id',
        'product_variant_id',
        'quantity',
        'price',
        'discount',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'discount' => 'integer',
        'total_price' => 'integer',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Calculate total price based on quantity, price, and discount
    protected static function boot()
    {
        parent::boot();

        static::creating(function (SaleItem $item) {
            // Calculate total price: (quantity * price) - discount percentage
            $subtotal = $item->quantity * $item->price;
            $discountAmount = $subtotal * ($item->discount / 100);
            $item->total_price = $subtotal - $discountAmount;
        });

        static::updating(function (SaleItem $item) {
            // Calculate total price: (quantity * price) - discount percentage
            $subtotal = $item->quantity * $item->price;
            $discountAmount = $subtotal * ($item->discount / 100);
            $item->total_price = $subtotal - $discountAmount;
        });

        static::saved(function (SaleItem $item) {
            // Update parent sale total amount
            $item->sale->updateTotalAmount();
            $item->sale->calculateChange();
        });

        static::deleted(function (SaleItem $item) {
            // Update parent sale total amount
            $item->sale->updateTotalAmount();
            $item->sale->calculateChange();
        });
    }
}