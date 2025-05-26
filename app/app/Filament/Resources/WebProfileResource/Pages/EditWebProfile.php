<?php

namespace App\Filament\Resources\WebProfileResource\Pages;

use App\Filament\Resources\WebProfileResource;
use App\Models\WebProfile;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;

class EditWebProfile extends Page
{
    protected static string $resource = WebProfileResource::class;

    protected static string $view = 'filament.resources.web-profile-resource.pages.edit-web-profile';
    
    protected static ?string $title = 'Website Settings';
    
    public ?array $data = [];
    
    public ?WebProfile $record = null;
    
    public function mount(): void
    {
        // Get the first record or create a new one if none exists
        $this->record = WebProfile::first() ?? WebProfile::create([
            'logo' => 'default-logo.png',
            'site_name' => 'My Awesome Site',
            'email' => 'admin@example.com',
            'phone' => '+6281234567890',
            'address' => 'Jl. Contoh No. 123, Jakarta, Indonesia',
            'facebook' => 'https://facebook.com/example',
            'instagram' => 'https://instagram.com/example',
            'twitter' => 'https://twitter.com/example',
        ]);
        
        $this->form->fill($this->record->attributesToArray());
    }
    
    public function form(Form $form): Form
    {
        return static::getResource()::form($form);
    }
    
    /**
     * Get the form schema.
     */
    protected function getFormSchema(): array
    {
        return $this->getResourceForm()->getSchema();
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        $this->record->update($data);
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}