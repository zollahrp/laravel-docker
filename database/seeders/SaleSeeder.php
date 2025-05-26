<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // ID admin
        $customerId = 1; // ID customer
        $variant = DB::table('product_variants')->first(); // Ambil varian produk pertama

        if (!$variant) return;

        // Buat sale
        $saleId = Str::uuid();
        $quantity = 2;
        $price = $variant->sale_price;
        $total = $quantity * $price;
        $paymentReceived = $total + 1000; // Pembayaran lebih

        DB::table('sales')->insert([
            'id' => $saleId,
            'customer_id' => $customerId,
            'total_amount' => $total,
            'payment_received' => $paymentReceived,
            'change' => $paymentReceived - $total,
            'payment_method' => 1, // Metode pembayaran (misal: 1 = Tunai)
            'notes' => 'Pembelian pertama',
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sale_items')->insert([
            'id' => Str::uuid(),
            'sale_id' => $saleId,
            'product_variant_id' => $variant->id,
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $total,
            'discount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
