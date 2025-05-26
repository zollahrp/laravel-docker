<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use App\Models\ProductVariant;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Products';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->required()
                    ->directory('products/thumbnails')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1200')
                    ->imageResizeTargetHeight('675'),

                Forms\Components\Section::make('Product Variants')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                Forms\Components\Section::make('Variant Information')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('sku')
                                                    ->required()
                                                    ->integer(),
                
                                                Forms\Components\Select::make('unit')
                                                    ->label('Satuan')
                                                    ->options(array_combine(ProductVariant::$units, ProductVariant::$units))
                                                    ->required()
                                                    ->native(false)
                                                    ->searchable(),    
                                                    
                                            ])->columns(3),
                                            
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('sale_price')
                                                    ->required()
                                                    ->numeric()
                                                    ->prefix('Rp'),
                                                    
                                                Forms\Components\TextInput::make('cost_price')
                                                    ->required()
                                                    ->numeric()
                                                    ->prefix('Rp'),
                                                    
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        1 => 'Active',
                                                        0 => 'Inactive',
                                                    ])
                                                    ->default('active')
                                                    ->required(),
                                            ])->columns(3),
                                            
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Toggle::make('useStock')
                                                    ->label('Track Inventory')
                                                    ->default(true)
                                                    ->live(),
                                                
                                                Forms\Components\TextInput::make('stock')
                                                    ->required()
                                                    ->default(0)
                                                    ->numeric()
                                                    ->visible(fn (Forms\Get $get) => $get('useStock')),
                                            ])->columns(2),
                                            
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('color')
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('size')
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('weight')
                                                    ->numeric()
                                                    ->suffix('g'),
                                                    
                                                Forms\Components\TextInput::make('dimensions')
                                                    ->maxLength(255)
                                                    ->placeholder('e.g. 10x5x2 cm'),
                                            ])->columns(4),
                                            
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('shopee_link')
                                                    ->url()
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('tokopedia_link')
                                                    ->url()
                                                    ->maxLength(255),
                                            ])->columns(2),
                                            
                                        Forms\Components\Textarea::make('description')
                                            ->columnSpanFull(),
                                    ]),
                                    
                                Forms\Components\Section::make('Product Images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('images')
                                            ->label('Product Images (Max 4)')
                                            ->multiple()
                                            ->maxFiles(4)
                                            ->directory('products/variants')
                                            ->visibility('public')
                                            ->image()
                                            ->imageResizeMode('cover')
                                            ->imageResizeTargetWidth('800')
                                            ->imageResizeTargetHeight('800')
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                if (count($state) > 4) {
                                                    $set('images', array_slice($state, 0, 4));
                                                }
                                            }),
                                    ]),
                            ])
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof \App\Filament\Resources\ProductResource\Pages\CreateProduct),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->square(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('variants_count')
                    ->label('Variants')
                    ->counts('variants')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => "{$state}"),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                    
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record) {
                        $record->delete();
                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
