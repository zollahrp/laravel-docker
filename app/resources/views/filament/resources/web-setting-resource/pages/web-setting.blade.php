<x-filament::page>
    <x-filament::section>
        <form wire:submit="save">
            {{ $this->form }}
            
            <div class="mt-6">
                <x-filament::button type="submit">
                    Save Settings
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament::page>