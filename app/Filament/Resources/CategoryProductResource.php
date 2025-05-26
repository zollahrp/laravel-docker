<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryProductResource\Pages;
use App\Filament\Resources\CategoryProductResource\RelationManagers;
use App\Models\CategoryProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryProductResource extends Resource
{
    protected static ?string $model = CategoryProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(function ($record) {
                        if (!$record || !$record->exists) {
                            // New category being created - can select any existing category
                            return CategoryProduct::pluck('name', 'id');
                        }
                        
                        // Get all descendant IDs to exclude them as potential parents
                        $excludeIds = self::getAllDescendantIds($record->id);
                        $excludeIds[] = $record->id; // Also exclude the category itself
                        
                        return CategoryProduct::whereNotIn('id', $excludeIds)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->placeholder('Select parent category (optional)')
                    ->nullable(),
            ]);
    }

    /**
     * Recursively get all descendant IDs for a category
     */
    protected static function getAllDescendantIds(string $categoryId, array &$ids = []): array
    {
        $childrenIds = CategoryProduct::where('parent_id', $categoryId)->pluck('id')->toArray();
        
        foreach ($childrenIds as $childId) {
            $ids[] = $childId;
            self::getAllDescendantIds($childId, $ids); // Recursive call to get children of children
        }
        
        return $ids;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->searchable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategoryProducts::route('/'),
        ];
    }
}