<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sale extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'customer_id',
        'total_amount',
        'payment_received',
        'change',
        'credit_amount',
        'payment_method',
        'status',
        'notes',
        'created_by',
        'deleted_by'
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'payment_received' => 'integer',
        'change' => 'integer',
        'credit_amount' => 'integer',
        'payment_method' => 'integer',
        'status' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Method to update total amount based on sale items
    public function updateTotalAmount(): void
    {
        $this->update([
            'total_amount' => $this->items->sum('total_price')
        ]);
    }

    // Calculate change and credit amount
    public function calculateChangeAndCredit(): void
    {
        $totalAmount = $this->total_amount;
        $paymentReceived = $this->payment_received;
        
        if ($paymentReceived >= $totalAmount) {
            // If payment is enough, set change and make credit amount 0
            $this->update([
                'change' => $paymentReceived - $totalAmount,
                'credit_amount' => 0,
                'status' => 1 // Lunas
            ]);
        } else {
            // If payment is less than total, set credit amount
            $this->update([
                'change' => 0,
                'credit_amount' => $totalAmount - $paymentReceived
            ]);
            
            // If payment method is credit, set status to "Belum Lunas"
            if ($this->payment_method == 4) {
                $this->update([
                    'status' => 2 // Belum Lunas
                ]);
            }
        }
    }

    // Method for invoice number
    public function getInvoiceNumberAttribute(): string
    {
        return 'INV-' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $sale->created_by = auth()->id();
            
            // Calculate credit amount if payment received is less than total
            if ($sale->payment_received < $sale->total_amount) {
                $sale->credit_amount = $sale->total_amount - $sale->payment_received;
                
                // If payment method is credit, set status to "Belum Lunas"
                if ($sale->payment_method == 4) {
                    $sale->status = 2; // Belum Lunas
                }
            } else {
                $sale->credit_amount = 0;
                $sale->status = 1; // Lunas
            }
        });

        static::created(function ($sale) {
            History::create([
                'description' => "Transaksi penjualan dibuat",
                'action' => 'create',
                'affected_table' => 'sales',
            ]);
        });

        static::deleting(function ($sale) {
            $sale->deleted_by = auth()->id();
            $sale->save();
        });

        static::deleted(function ($sale) {
            History::create([
                'description' => "Transaksi penjualan dihapus",
                'action' => 'delete',
                'affected_table' => 'sales',
            ]);
        });
    }
}