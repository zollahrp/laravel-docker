<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Sale $sale)
    {
        return view('invoices.show', compact('sale'));
    }
}
