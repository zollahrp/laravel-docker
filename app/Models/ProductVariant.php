<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'sku',
        'product_id',
        'unit',
        'stock',
        'useStock',
        'shopee_link',
        'tokopedia_link',
        'description',
        'weight',
        'dimensions',
        'color',
        'size',
        'sale_price',
        'cost_price',
        'status',
        'created_by',
        'deleted_by',
    ];

    public static $units = [
        'pcs',     // Per item (umum digunakan)
        'box',     // Sekotak (berisi beberapa item)
        'pack',    // Paket/kemasan (bisa isi 5, 10, dll.)
        'set',     // Sekumpulan item (misalnya set obeng)
        'kg',      // Kilogram, untuk barang berbobot
        'g',       // Gram, untuk barang dengan bobot kecil
        'l',       // Liter, untuk cairan
        'ml',      // Milliliter, untuk cairan dalam jumlah kecil
        'm',       // Meter, untuk barang berbasis panjang
        'cm',      // Centimeter, untuk ukuran kecil berbasis panjang
        'roll',    // Barang yang digulung (kabel, kain, dll.)
        'lembar',   // Lembar (kertas, logam, kain)
        'lusin',   // 12 pcs (lusin)
        'gross',   // 144 pcs (12 lusin)
        'unit',    // Umum untuk produk elektronik atau kendaraan
        'botol',  // Untuk produk dalam botol
        'kaleng',     // Kaleng
        'toples',     // Toples
        'sepasang',    // Sepasang (misalnya sepatu, sarung tangan)
        'karton',  // Karton besar (biasanya isi banyak box)
        'bal',     // Ikatan (misalnya kain, kertas)
    ];

    /**
     * Mendapatkan daftar satuan unit stok.
     *
     * @return array
     */
    public static function getAvailableUnits()
    {
        return self::$units;
    }

    /**
     * Get the product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the variant.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who deleted the variant.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the images for the variant.
     */
    public function images()
    {
        return $this->hasMany(ProductVariantImage::class);
    }

    /**
     * Record activity in history log
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($variant) {
            History::create([
                'description' => "Product variant '{$variant->name}' dibuat",
                'action' => 'create',
                'affected_table' => 'product_variants',
            ]);
        });

        static::updated(function ($variant) {
            History::create([
                'description' => "Product variant '{$variant->name}' diupdate",
                'action' => 'update',
                'affected_table' => 'product_variants',
            ]);
        });

        static::deleted(function ($variant) {
            History::create([
                'description' => "Product variant '{$variant->name}' dihapus",
                'action' => 'delete',
                'affected_table' => 'product_variants',
            ]);
        });
    }
}
