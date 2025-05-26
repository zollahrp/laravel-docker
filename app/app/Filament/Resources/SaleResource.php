<?php

namespace App\Filament\Resources;

use Filament\Notifications\Notification;
use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $modelLabel = 'Penjualan';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Penjualan')
                            ->schema([
                                Select::make('customer_id')
                                    ->label('Pelanggan')
                                    ->options(Customer::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        1 => 'Lunas',
                                        2 => 'Belum Lunas',
                                    ])
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state == 1) { // Lunas
                                            $set('credit_amount', 0);
                                            
                                            // Calculate total
                                            $items = $get('items') ?? [];
                                            $totalAmount = collect($items)->reduce(function ($total, $item) {
                                                $quantity = (int) $item['quantity'];
                                                $price = (int) $item['price'];
                                                $discount = (int) ($item['discount'] ?? 0);
                                                
                                                $subtotal = $quantity * $price;
                                                $discountAmount = $subtotal * ($discount / 100);
                                                
                                                return $total + ($subtotal - $discountAmount);
                                            }, 0);
                                            
                                            $set('payment_received', $totalAmount);
                                            $set('change', 0);
                                        } else { // Belum Lunas
                                            // Let admin set payment_received and credit_amount will be calculated
                                        }
                                    }),

                            ])->columns(2),

                        Section::make('Item Penjualan')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship()
                                    ->label('')
                                    ->schema([
                                        Select::make('product_variant_id')
                                            ->label('Produk')
                                            ->options(ProductVariant::with('product')->get()->mapWithKeys(function ($variant) {
                                                return [$variant->id => $variant->product->name . ' - ' . $variant->name];
                                            }))
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                $variant = ProductVariant::find($state);
                                                if ($variant) {
                                                    $set('price', $variant->sale_price);
                                                }
                                            }),
                                            
                                        TextInput::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->reactive(),
                                            
                                        TextInput::make('price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $variantId = $get('product_variant_id');
                                                if ($variantId) {
                                                    $variant = ProductVariant::find($variantId);
                                                    if ($variant && $state < $variant->cost_price) {
                                                        // Show warning notification for price below cost
                                                        $costPriceWarning = "Harga jual Rp " . number_format($state, 0, ',', '.') . " lebih rendah dari harga pokok Rp " . number_format($variant->cost_price, 0, ',', '.');
                                                        
                                                        // Use notification instead of Halt exception
                                                        Notification::make()
                                                            ->warning()
                                                            ->title('Peringatan Harga')
                                                            ->body($costPriceWarning)
                                                            ->persistent()
                                                            ->send();
                                                    }
                                                }
                                            }),
                                            
                                        TextInput::make('discount')
                                            ->label('Diskon (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->reactive(),
                                            
                                        Placeholder::make('total_price_display')
                                            ->label('Total')
                                            ->content(function (Get $get) {
                                                $quantity = (int) $get('quantity');
                                                $price = (int) $get('price');
                                                $discount = (int) $get('discount');
                                                
                                                $subtotal = $quantity * $price;
                                                $discountAmount = $subtotal * ($discount / 100);
                                                $total = $subtotal - $discountAmount;
                                                
                                                return 'Rp ' . number_format($total, 0, ',', '.');
                                            }),
                                    ])
                                    ->deleteAction(
                                        fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                                    )
                                    ->itemLabel(fn (array $state): ?string => 
                                        $state['product_variant_id'] 
                                            ? ProductVariant::find($state['product_variant_id'])->product->name . ' - ' . 
                                              ProductVariant::find($state['product_variant_id'])->name
                                            : null
                                    )
                                    ->defaultItems(0)
                                    ->reorderable(false)
                                    ->reactive()
                                    ->columns(5)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        // Recalculate total and credit amount whenever items change
                                        $items = $get('items') ?? [];
                                        $totalAmount = collect($items)->reduce(function ($total, $item) {
                                            $quantity = (int) $item['quantity'];
                                            $price = (int) $item['price'];
                                            $discount = (int) ($item['discount'] ?? 0);
                                            
                                            $subtotal = $quantity * $price;
                                            $discountAmount = $subtotal * ($discount / 100);
                                            
                                            return $total + ($subtotal - $discountAmount);
                                        }, 0);
                                        
                                        $set('total_amount', $totalAmount);
                                        
                                        // Update credit amount if status is "Belum Lunas"
                                        if ((int) $get('status') === 2) {
                                            $paymentReceived = (int) $get('payment_received');
                                            $creditAmount = max(0, $totalAmount - $paymentReceived);
                                            $set('credit_amount', $creditAmount);
                                        }
                                    }),
                            ]),
                    ])
                    ->columnSpan(['lg' => 8]),

                Group::make()
                    ->schema([
                        Section::make('Pembayaran')
                            ->schema([
                                Placeholder::make('total_amount_display')
                                    ->label('Total Pembelian')
                                    ->content(function (Get $get) {
                                        $items = $get('items') ?? [];
                                        $totalAmount = collect($items)->reduce(function ($total, $item) {
                                            $quantity = (int) $item['quantity'];
                                            $price = (int) $item['price'];
                                            $discount = (int) ($item['discount'] ?? 0);
                                            
                                            $subtotal = $quantity * $price;
                                            $discountAmount = $subtotal * ($discount / 100);
                                            
                                            return $total + ($subtotal - $discountAmount);
                                        }, 0);
                                        
                                        return 'Rp ' . number_format($totalAmount, 0, ',', '.');
                                    }),
                                    
                                TextInput::make('total_amount')
                                    ->label('Total Pembelian (Hidden)')
                                    ->numeric()
                                    ->hidden()
                                    ->reactive()
                                    ->afterStateHydrated(function (Set $set, Get $get) {
                                        $items = $get('items') ?? [];
                                        $totalAmount = collect($items)->reduce(function ($total, $item) {
                                            $quantity = (int) $item['quantity'];
                                            $price = (int) $item['price'];
                                            $discount = (int) ($item['discount'] ?? 0);
                                            
                                            $subtotal = $quantity * $price;
                                            $discountAmount = $subtotal * ($discount / 100);
                                            
                                            return $total + ($subtotal - $discountAmount);
                                        }, 0);
                                        
                                        $set('total_amount', $totalAmount);
                                    }),
                                    
                                TextInput::make('payment_received')
                                    ->label('Jumlah Pembayaran')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $totalAmount = 0;
                                        $items = $get('items') ?? [];
                                        
                                        $totalAmount = collect($items)->reduce(function ($total, $item) {
                                            $quantity = (int) $item['quantity'];
                                            $price = (int) $item['price'];
                                            $discount = (int) ($item['discount'] ?? 0);
                                            
                                            $subtotal = $quantity * $price;
                                            $discountAmount = $subtotal * ($discount / 100);
                                            
                                            return $total + ($subtotal - $discountAmount);
                                        }, 0);
                                        
                                        $payment = (int) $state;
                                        
                                        // Calculate change or credit amount
                                        if ($payment >= $totalAmount) {
                                            $set('change', $payment - $totalAmount);
                                            $set('credit_amount', 0);
                                            $set('status', 1); // Lunas
                                        } else {
                                            $set('change', 0);
                                            $set('credit_amount', $totalAmount - $payment);
                                            
                                            // Don't automatically set status - leave it to admin
                                            // Only suggest payment method if admin hasn't selected credit
                                            if ((int) $get('payment_method') !== 4) {
                                                $paymentShortage = "Pembayaran kurang Rp " . number_format($totalAmount - $payment, 0, ',', '.') . ". Apakah ini transaksi kredit?";
                                                
                                                // Use notification instead of Halt exception
                                                Notification::make()
                                                    ->warning()
                                                    ->title('Transaksi Kredit?')
                                                    ->body($paymentShortage)
                                                    ->persistent()
                                                    ->send();
                                            }
                                        }
                                    }),
                                    
                                TextInput::make('credit_amount')
                                    ->label('Jumlah Kredit')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->visible(fn (Get $get): bool => 
                                        (int) $get('status') === 2 || 
                                        (int) $get('payment_method') === 4 ||
                                        (int) $get('payment_received') < (int) $get('total_amount')
                                    ),
                                    
                                TextInput::make('change')
                                    ->label('Kembalian')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),
                                    
                                Select::make('payment_method')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        1 => 'Tunai',
                                        2 => 'QRIS',
                                        3 => 'Transfer',
                                        4 => 'Kredit',
                                    ])
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        // If payment method is credit, set status to "Belum Lunas"
                                        if ($state == 4) {
                                            $set('status', 2); // Belum Lunas
                                        }
                                    }),
                                    
                                Textarea::make('notes')
                                    ->label('Catatan')
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpan(['lg' => 4]),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No. Invoice')
                    ->formatStateUsing(fn (Sale $record): string => $record->getInvoiceNumberAttribute())
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('payment_received')
                    ->label('Dibayar')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('credit_amount')
                    ->label('Kredit')
                    ->money('IDR')
                    ->sortable()
                    ->visible(function ($record) {
                        // Always show the credit amount column for simplicity
                        // or conditionally based on the record itself
                        return true;
                    }),
                    
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Tunai',
                        '2' => 'QRIS',
                        '3' => 'Transfer',
                        '4' => 'Kredit',
                        default => 'Unknown',
                    }),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Lunas',
                        2 => 'Belum Lunas',
                        default => 'Unknown',
                    })
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        1 => 'Tunai',
                        2 => 'QRIS',
                        3 => 'Transfer',
                        4 => 'Kredit',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Lunas',
                        2 => 'Belum Lunas',
                    ]),
                    
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}