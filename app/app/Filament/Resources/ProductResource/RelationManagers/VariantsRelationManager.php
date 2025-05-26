<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ProductVariant;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
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
                                    ->default(1)
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.0.image')
                    ->label('Image')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('sale_price')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('status')
                    ->options([
                        1 => 'heroicon-o-check-circle',
                        0 => 'heroicon-o-x-circle',
                    ])
                    ->colors([
                        'success' => 1,
                        'danger' => 0,
                    ]),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['created_by'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record) {
                        $record->update(['deleted_by' => Auth::id()]);
                        $record->delete();
                        
                        return true;
                    }),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['deleted_by' => Auth::id()]);
                                $record->delete();
                            });
                            
                            return true;
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}