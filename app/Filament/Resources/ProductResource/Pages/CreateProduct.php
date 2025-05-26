<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected $variants;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $variants = $data['variants'] ?? [];
        unset($data['variants']);

        $data['created_by'] = Auth::id();
        $this->variants = $variants;

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->variants as $variant) {
            $v = $this->record->variants()->create([
                'name' => $variant['name'],
                'price' => $variant['price'],
                'stock' => $variant['stock'],
            ]);

            // Simpan gambar jika ada
            if (!empty($variant['images'])) {
                foreach ($variant['images'] as $image) {
                    $v->addMedia($image->getRealPath())
                        ->usingFileName($image->getClientOriginalName())
                        ->toMediaCollection('variant_images');
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
