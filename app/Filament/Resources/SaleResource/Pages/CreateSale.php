<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;
    
    protected function beforeCreate(): void
    {
        // Calculate total amount from items
        $items = $this->data['items'] ?? [];
        $totalAmount = collect($items)->reduce(function ($total, $item) {
            $quantity = (int) $item['quantity'];
            $price = (int) $item['price'];
            $discount = (int) ($item['discount'] ?? 0);
            
            $subtotal = $quantity * $price;
            $discountAmount = $subtotal * ($discount / 100);
            
            return $total + ($subtotal - $discountAmount);
        }, 0);
        
        $this->data['total_amount'] = $totalAmount;
        
        // Calculate credit amount if payment received is less than total
        $paymentReceived = (int) $this->data['payment_received'];
        if ($paymentReceived < $totalAmount) {
            $this->data['credit_amount'] = $totalAmount - $paymentReceived;
            
            // Set status to "Belum Lunas" if payment method is credit
            if ((int) $this->data['payment_method'] === 4) {
                $this->data['status'] = 2; // Belum lunas
            }
        } else {
            $this->data['credit_amount'] = 0;
            $this->data['status'] = 1; // Lunas
        }
        
        // Calculate change
        $this->data['change'] = max(0, $paymentReceived - $totalAmount);
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}