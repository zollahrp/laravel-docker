<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Landing;
use App\Livewire\Pages\Products\DetailProduct;
use App\Http\Controllers\InvoiceController;

Route::get('/sales/{sale}/invoice', [InvoiceController::class, 'show'])->name('sales.invoice');

Route::get('/', Landing::class);
Route::get('/detail', DetailProduct::class);

