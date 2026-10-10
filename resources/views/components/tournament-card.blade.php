@props(['tournament'])

<div class="block max-w-sm bg-white p-4 border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <div class="flex justify-between items-center mb-2">
        <x-share-code-modal :tournament="$tournament" class="text-xs font-mono text-gray-400 hover:text-blue-500 dark:text-gray-500 dark:hover:text-blue-400">
            {{ $tournament->share_code }}
        </x-share-code-modal>
        <x-danger-button wire:confirm="{{ __('Delete') }} ?" wire:click="delete({{ $tournament->id }})">{{ __('X') }}</x-danger-button>
    </div>
    <a href="/tournaments/{{ $tournament->id }}/registration">
        <div>
            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-gray-200">{{ $tournament->label }}</h5>
            <div class="font-normal text-gray-700 dark:text-gray-400">
                <p class="text-xs">{{ Carbon\Carbon::parse($tournament->date)->format('j M Y') }},
                    <span>{{ $tournament->date == date('Y-m-d') ?
                __('Today') : Carbon\Carbon::parse($tournament->date)->diffForHumans() }}</span>
                </p>
                <p>{{ $tournament->place }}</p>
                <p>
                    @if($tournament->team_size == 0)
                        {{ __('Precision') }}
                    @else
                        @if($tournament->team_size == 1)
                            {{ __('Simple') }}
                        @elseif($tournament->team_size == 2)
                            {{ __('Double') }}
                        @elseif($tournament->team_size == 3)
                            {{ __('Triple') }}
                        @endif
                        -
                        @if($tournament->has_brackets)
                            {{ __('Pools') }}
                        @else
                            {{ __('Playoff') }}
                        @endif
                    @endif
                </p>
            </div>
        </div>
    </a>
</div>
