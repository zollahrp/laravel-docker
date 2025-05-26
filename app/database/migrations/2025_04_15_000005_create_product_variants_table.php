<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('sku');
            $table->uuid('product_id');
            $table->string('unit')->comment('Satuan unit stok, misalnya: pcs, box, pack, set, kg, liter, dll.');
            $table->integer('stock');
            $table->boolean('useStock')->default(true);
            $table->string('shopee_link')->nullable();
            $table->string('tokopedia_link')->nullable();
            $table->text('description')->nullable();
            $table->integer('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('sale_price');
            $table->integer('cost_price');
            $table->integer('status')->default(1)->comment('1: active, 0: inactive'); 
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
