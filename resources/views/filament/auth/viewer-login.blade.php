@if (\App\Models\AdminSetting::isViewerModeEnabled())
    <div class="mt-6 text-center">
        <x-filament::button
            color="gray"
            tag="a"
            href="{{ route('viewer.start') }}"
        >
            I am a viewer
        </x-filament::button>
    </div>
@endif
