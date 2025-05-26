<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Invoice {{ $sale->invoice_number }}</h1>
    <p><strong>Customer:</strong> {{ $sale->customer_name }}</p>
    <p><strong>Date:</strong> {{ $sale->date->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->discount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
    <p class="total">Paid: Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</p>
    <p class="total">Change: Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</p>

    <p><em>Thank you for your purchase!</em></p>
</body>
</html>
