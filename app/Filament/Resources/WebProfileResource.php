<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebProfileResource\Pages;
use App\Models\WebProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class WebProfileResource extends Resource
{
    protected static ?string $model = WebProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    
    protected static ?string $navigationLabel = 'Website Settings';
    
    protected static ?string $modelLabel = 'Website Profile';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General Information')
                ->schema([
                    Forms\Components\TextInput::make('logo')
                        ->label('Website Logo')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('site_name')
                        ->label('Website Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('address')
                        ->required()
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Social Media')
                ->schema([
                    Forms\Components\TextInput::make('facebook')
                        ->label('Facebook URL')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('instagram')
                        ->label('Instagram URL')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('twitter')
                        ->label('Twitter URL')
                        ->url()
                        ->maxLength(255),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditWebProfile::route('/'),
        ];
    }
}