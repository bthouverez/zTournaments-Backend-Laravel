<div class="flex justify-around m-6 flex-wrap md:flex-nowrap gap-4">
    <form wire:submit="createTournament"
          class="bg-white dark:bg-gray-800  shadow-md rounded px-8 pt-6 pb-8 mb-4 grow-0 w-80 min-w-max">
        <h1 class="text-xl font-bold dark:text-gray-300">{{ __('Create new tournament') }}</h1>
        <div class="my-4">
            <p class="font-bold p-2 dark:text-gray-300">{{ __('Type') }}</p>
            @error('team_size') <p class="text-red-500">{{ $message }}</p> @enderror
            <ul class="grid md:grid-cols-2 text-center">
                <li>
                    <x-radio wire:click="setTeamSize(0)" value="precision" name="team_size"
                             class="rounded-t-lg md:rounded-tr-none">
                        {{ __('Precision') }}
                    </x-radio>
                </li>
                <li>
                    <x-radio wire:click="setTeamSize(1)" value="simple" name="team_size" class="md:rounded-tr-lg">
                        {{ __('Simple') }}
                    </x-radio>
                </li>
                <li>
                    <x-radio wire:click="setTeamSize(2)" value="double" name="team_size" class="md:rounded-bl-lg">
                        {{ __('Double') }}
                    </x-radio>
                </li>
                <li>
                    <x-radio wire:click="setTeamSize(3)" value="triple" name="team_size"
                             class="rounded-b-lg md:rounded-bl-none">
                        {{ __('Triple') }}
                    </x-radio>
                </li>
            </ul>
        </div>
        <div class="my-4">
            <p class="font-bold p-2 dark:text-gray-300">{{ __('Format') }}</p>
            @error('has_brackets') <p class="text-red-500">{{ $message }}</p> @enderror
            <ul class="grid w-full md:grid-cols-2 text-center">
                <li>
                    <x-radio wire:click="setHasBracket(1)" value="bracket" name="has_brackets"
                             class="rounded-t-lg md:rounded-tr-none md:rounded-bl-lg">
                        {{ __('Bracket') }}
                    </x-radio>
                </li>
                <li>
                    <x-radio wire:click="setHasBracket(0)" value="playoff" name="has_brackets"
                             class="rounded-b-lg md:rounded-bl-none md:rounded-tr-lg">
                        {{ __('Playoff') }}
                    </x-radio>
                </li>
            </ul>
        </div>

        <div class="my-4">
            {{ __('Melee') }} ?
            <x-checkbox wire:model="melee" name="melee" id="melee" class="mx-2"/>
        </div>

        <div class="mb-4">
            @error('label') <p class="text-red-500">{{ $message }}</p> @enderror
            <x-input class="w-full"
                     wire:model="label" name="label" id="label" type="text" placeholder="{{ __('Label') }}"></x-input>
        </div>
        <div class="mb-4">
            @error('date') <p class="text-red-500">{{ $message }}</p> @enderror
            <x-input class="w-full"
                     wire:model="date" name="date" id="date" type="date"></x-input>
        </div>
        <div class="mb-6">
            @error('place') <p class="text-red-500">{{ $message }}</p> @enderror
            <x-input class="w-full"
                     wire:model="place" name="place" id="place" type="text" placeholder="{{ __('Place') }}"></x-input>
        </div>


        <div class="flex items-center justify-end">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ __('Create') }}
            </button>
        </div>
    </form>
    <div class="flex flex-col gap-4 grow">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-6">
            <h2 class="font-bold mb-3 dark:text-white">{{ __('Join a tournament') }}</h2>
            <form wire:submit="joinTournament" class="flex items-start gap-2">
                <div>
                    <x-input wire:model="joinCode" type="text" maxlength="6"
                             placeholder="{{ __('Join code') }}" class="uppercase tracking-widest"></x-input>
                    @error('joinCode') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('Join') }}
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8">
            <h1 class="text-xl font-bold mb-3 dark:text-white">{{ __('My tournaments') }}</h1>
            <div class="flex gap-3 flex-wrap">
                @forelse($tournaments->sortByDesc('date') as $tournament)
                    <x-tournament-card :tournament="$tournament" />
                @empty
                    <p class=" dark:text-gray-300">{{ __('No tournament registered') }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8">
            <h1 class="text-xl font-bold mb-3 dark:text-white">{{ __('Shared with me') }}</h1>
            <div class="flex gap-3 flex-wrap">
                @forelse($sharedTournaments->sortByDesc('date') as $tournament)
                    <x-tournament-card :tournament="$tournament" />
                @empty
                    <p class=" dark:text-gray-300">{{ __('No shared tournament') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
