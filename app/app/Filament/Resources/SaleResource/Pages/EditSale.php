<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    
    protected function beforeSave(): void
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
        
        // Calculate credit amount based on status and payment received
        $paymentReceived = (int) $this->data['payment_received'];
        $status = (int) $this->data['status'];
        
        if ($status == 1) { // Lunas
            $this->data['credit_amount'] = 0;
            $this->data['payment_received'] = $totalAmount;
            $this->data['change'] = 0;
            
            // If payment received is manually set higher, calculate change
            if ($paymentReceived > $totalAmount) {
                $this->data['change'] = $paymentReceived - $totalAmount;
            }
        } else { // Belum Lunas
            $this->data['credit_amount'] = max(0, $totalAmount - $paymentReceived);
            $this->data['change'] = 0;
            
            // If payment method is credit, ensure status is "Belum Lunas"
            if ((int) $this->data['payment_method'] === 4) {
                $this->data['status'] = 2; // Belum lunas
            }
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}