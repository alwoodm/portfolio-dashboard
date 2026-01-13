<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-3">
            <x-filament::button type="submit">
                Save
            </x-filament::button>

            @if ($this->isViewerModeEnabled())
                <x-filament::button
                    color="gray"
                    tag="a"
                    href="{{ route('viewer.start') }}"
                >
                    I am a viewer
                </x-filament::button>
            @endif
        </div>
    </form>
</x-filament-panels::page>
