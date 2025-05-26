<?php

namespace App\Filament\Resources\CategoryProductResource\Pages;

use App\Filament\Resources\CategoryProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCategoryProducts extends ManageRecords
{
    protected static string $resource = CategoryProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
