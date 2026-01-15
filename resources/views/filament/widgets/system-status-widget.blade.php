<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            System Status
        </x-slot>

        <div class="space-y-3">
            {{-- Status Row --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Portfolio API</span>

                @php
                    $status = $this->status ?? 'error';
                    $textClasses = match($status) {
                        'connected' => 'text-green-600 dark:text-green-400',
                        'not_configured' => 'text-yellow-600 dark:text-yellow-400',
                        default => 'text-red-600 dark:text-red-400',
                    };
                @endphp

                <span class="inline-flex items-center gap-1.5 text-sm font-medium {{ $textClasses }}">
                    <x-dynamic-component :component="$this->getStatusIcon()" class="h-4 w-4" />
                    {{ $this->getStatusLabel() }}
                </span>
            </div>

            {{-- Response Time --}}
            @if ($this->responseTime !== null)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Response time</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $this->responseTime }}ms
                    </span>
                </div>
            @endif

            {{-- Error --}}
            @if ($this->error)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Error</span>
                    <span class="text-sm font-medium text-red-600 dark:text-red-400 truncate max-w-[180px]" title="{{ $this->error }}">
                        {{ Str::limit($this->error, 25) }}
                    </span>
                </div>
            @endif

            {{-- Last Check with Live Timer --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Last check</span>
                <span
                    class="text-sm font-medium text-gray-900 dark:text-white"
                    x-data="{
                        seconds: 0,
                        init() {
                            setInterval(() => { this.seconds++ }, 1000)
                        },
                        get display() {
                            if (this.seconds < 60) {
                                return this.seconds + ' seconds ago'
                            } else if (this.seconds < 3600) {
                                const mins = Math.floor(this.seconds / 60)
                                return mins + (mins === 1 ? ' minute ago' : ' minutes ago')
                            } else {
                                const hours = Math.floor(this.seconds / 3600)
                                return hours + (hours === 1 ? ' hour ago' : ' hours ago')
                            }
                        }
                    }"
                    x-text="display"
                    @check-health.window="seconds = 0"
                    wire:ignore
                >
                    0 seconds ago
                </span>
            </div>

            {{-- Refresh Button --}}
            <div class="pt-1">
                <x-filament::button
                    wire:click="checkHealth"
                    x-on:click="$dispatch('check-health')"
                    size="sm"
                    color="gray"
                    icon="heroicon-o-arrow-path"
                    class="w-full"
                >
                    Check Now
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
