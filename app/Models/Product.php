<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'category_id',
        'created_by',
        'deleted_by',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    /**
     * Get the user who created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who deleted the product.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the variants for the product.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Record activity in history log
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            History::create([
                'description' => "Product '{$product->name}' dibuat",
                'action' => 'create',
                'affected_table' => 'products',
            ]);
        });

        static::updated(function ($product) {
            History::create([
                'description' => "Product '{$product->name}' diupdate",
                'action' => 'update',
                'affected_table' => 'products',
            ]);
        });

        static::deleted(function ($product) {
            History::create([
                'description' => "Produk '{$product->name}' dihapus",
                'action' => 'delete',
                'affected_table' => 'products',
            ]);
        });
    }
}