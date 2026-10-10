@props(['tournament'])

<div x-data="{ open: false, copied: false }" class="inline-block">
    <button type="button" @click="open = true" {{ $attributes->merge(['class' => '']) }}>
        {{ $slot->isEmpty() ? __('Share code') . ' ' . $tournament->share_code : $slot }}
    </button>

    <div x-show="open" x-cloak @keydown.escape.window="open = false"
         class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" style="display: none;">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="open = false"></div>

        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-sm sm:mx-auto relative px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Share this tournament') }}</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Share this code with whoever should help manage this tournament.') }}</p>

            <div class="mt-4 flex flex-col items-center gap-3">
                <canvas x-ref="qr" x-init="renderShareQr($refs.qr, @js(url('/join/' . $tournament->share_code)))"></canvas>

                <div class="flex items-center gap-3">
                    <span class="text-2xl font-mono font-bold tracking-widest dark:text-gray-100">{{ $tournament->share_code }}</span>
                    <button type="button"
                            @click="navigator.clipboard.writeText(@js($tournament->share_code)); copied = true; setTimeout(() => copied = false, 1500)"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm">
                        <span x-show="!copied">{{ __('Copy') }}</span>
                        <span x-show="copied" x-cloak>{{ __('Copied!') }}</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button type="button" @click="open = false"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 font-semibold py-2 px-4 rounded">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
