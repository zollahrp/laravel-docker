<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // ID admin
        $categoryId = null;

        // Membuat kategori produk
        DB::table('category_products')->insert([
            [
                'id' => $parent1 = Str::uuid(),
                'name' => 'Elektronik',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => $parent2 = Str::uuid(),
                'name' => 'Fashion',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Audio',
                'parent_id' => $parent1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pakaian Pria',
                'parent_id' => $parent2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Ambil ID kategori pertama
        $categoryId = DB::table('category_products')->first()->id ?? Str::uuid();

        // Membuat produk pertama
        $productId = Str::uuid();
        DB::table('products')->insert([
            'id' => $productId,
            'name' => 'Botol Minum Daur Ulang',
            'description' => 'Botol minum ramah lingkungan dari plastik daur ulang.',
            'thumbnail' => 'botol-thumbnail.jpg',
            'category_id' => $categoryId,
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Membuat varian produk
        for ($i = 1; $i <= 3; $i++) {
            $variantId = Str::uuid();
            DB::table('product_variants')->insert([
                'id' => $variantId,
                'name' => "Botol Size $i",
                'sku' => 1000 + $i,
                'product_id' => $productId,
                'unit' => 'pcs',
                'stock' => 50 * $i,
                'useStock' => true,
                'shopee_link' => null,
                'tokopedia_link' => null,
                'description' => "Varian ukuran $i",
                'weight' => 100 * $i,
                'dimensions' => '10x10x20',
                'color' => 'Hijau',
                'size' => "$i L",
                'sale_price' => 20000 * $i,
                'cost_price' => 15000 * $i,
                'status' => 1,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Menambahkan gambar ke varian produk
            for ($j = 1; $j <= 2; $j++) {
                DB::table('product_variant_images')->insert([
                    'id' => Str::uuid(),
                    'product_variant_id' => $variantId,
                    'image' => "botol-variant-$i-$j.jpg",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
