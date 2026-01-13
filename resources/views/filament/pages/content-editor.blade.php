<x-filament::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            @if ($this->canEdit())
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            @else
                <x-filament::badge color="gray">
                    Read-only
                </x-filament::badge>
            @endif
        </div>
    </form>
</x-filament::page>
