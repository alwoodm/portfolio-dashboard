<x-filament-widgets::widget>
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-950 dark:text-white">
                    Welcome back, {{ $this->getUserName() }}!
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->getCurrentDate() }}
                </p>
            </div>

            @if ($this->isViewerModeEnabled())
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 text-sm font-medium">
                    <x-heroicon-o-eye class="h-4 w-4" />
                    <span>Viewer mode enabled</span>
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
