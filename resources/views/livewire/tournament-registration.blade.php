<div>
    <section class="md:w-2/3 m-auto">
        <h1 class="text-2xl font-extrabold dark:text-gray-200">{{ __('Registration') }} - {{ $tournament->label }}</h1>
        <h2 class="dark:text-gray-200">@if($tournament->team_size == 0)
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
        </h2>
        <a href="/tournaments">
            <button
                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                < {{ __('Back') }}
            </button>
        </a>
    </section>

    <div class="flex flex-wrap mt-8 md:w-2/3 m-auto gap-2">
        <div class="border border-solid border-grey-800 border-2 md:p-8 p-2  rounded drop-shadow-sm">
            <button
                {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}} {{ $tournament->teams->count() > 1 ? "" : 'disabled' }}
                wire:click="generate"
                class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded
                        disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                {{ __('Generate') }}
            </button>
            <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}}
                    wire:click="resetTeams" {{ $tournament->teams->count() ? "" : 'disabled' }}
                    class="mb-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded
                disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                {{ __('Delete teams') }}
            </button>

            <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}}
                    wire:click="resetMatches" {{ $tournament->brackets->count()  || $tournament->matches->count() ? "" : 'disabled' }}
                    class="mb-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded
                    disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                {{ __('Delete matches') }}
            </button>

            <a href="/tournaments/{{ $tournament->id }}/{{ $nextStep }}">
                <button {{ $tournament->matches->count() ? '' : 'disabled' }}
                    class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded
                    disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">

                {{ __('Next') }} >
                </button>
            </a>


            @if(!$tournament->matches->count())
                <div class="flex gap-8 justify-between">
                    <div class="flex flex-col gap-2">
                        <h3 class="font-bold mt-2 dark:text-gray-200">{{ __('Add team') }}</h3>
                        <input wire:keydown.enter="addTeam"
                               class="shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                               type="text" wire:model.lazy="newPlayer1" placeholder="{{ __('Player') }} 1">
                        @if($tournament->team_size > 1)
                            <input wire:keydown.enter="addTeam"
                                   class="shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                                   type="text" wire:model.lazy="newPlayer2" placeholder="{{ __('Player') }} 2">
                            @if($tournament->team_size > 2)
                                <input wire:keydown.enter="addTeam"
                                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                                       type="text" wire:model.lazy="newPlayer3" placeholder="{{ __('Player') }} 3">
                            @endif
                        @endif
                        <button
                            wire:click="addTeam"
                            class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-3 border border-blue-500 hover:border-transparent rounded">
                            {{ __('Add') }}
                        </button>
                    </div>
                    <div class="flex flex-col gap-2">

                        <h3 class="font-bold mt-2 dark:text-gray-200">{{ __('Load file') }}</h3>
                        <form wire:submit="loadPlayersFile" class="flex flex-col gap-2">
                            <input type="file" wire:model="playersListFile">
                            <button
                                class="bg-transparent hover:bg-teal-500 text-teal-700 font-semibold hover:text-white py-2 px-3 border border-teal-500 hover:border-transparent rounded">
                                {{ __('Load') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        @if(count($tournament->teams->sortBy('label')))
            <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                <h3 class="font-bold mt-2 dark:text-gray-200">{{ __('Teams') }} ({{ $tournament->teams->count() }})</h3>
{{--                <div class="flex flex-wrap gap-4 md:m-4">--}}
                <div class="grid grid-cols-6 gap-5">
                    @foreach($tournament->teams->sortBy('number') as $team)
                        <div class="border border-gray-500 rounded pb-2">
                            <div class="flex justify-between">
                                <p class="text-lg font-bold p-2 dark:text-gray-200">{{ $team->label }}</p>
                                <button wire:click="removeTeam({{ $team->id }})"
                                        class="text-center bg-transparent hover:bg-red-500 text-red-500 hover:text-white
                                        w-5 h-5 mt-3 mr-2  border border-2 border-red-500 hover:border-transparent rounded">
                                </button>
                            </div>

                            <hr class="border border-1 border-gray-500 mb-2">
                            @foreach($team->players as $player)
                                <p class="mx-5 dark:text-gray-200">{{ $player->name }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
            @else
                <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                    <p class="mt-4 dark:text-gray-200">{{ __('No team registered') }}</p>
                </section>
            @endif

        @if($tournament->brackets->count())
            <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                <h1 class="text-xl font-bold dark:text-gray-200">
                    {{ __('Pools') }} ({{ $tournament->brackets->count() }})
                </h1>
                <ul class="grid grid-cols-6 gap-8">
                    @php($cpt=0)
                    @foreach($tournament->brackets->sortBy('label') as $bracket)
                        <li class="border border-black p-4 rounded">
                            <p class="font-bold dark:text-gray-200 pb-2">{{ __('Pool') }} {{ $bracket->label }}<br> {{ __('Fields') }} {{ ++$cpt }}
                                & {{ ++$cpt }}</p>
                            <hr class="border-black py-2">
                            @foreach($bracket->bracket_teams as $team)
                                <ul>
                                    <li class="dark:text-gray-200">{{ $team->label }}</li>
                                </ul>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if($tournament->brackets->count())
            <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                <h1 class="text-xl font-bold dark:text-gray-200">{{ __('Pools matches') }}</h1>
                @foreach($tournament->brackets as $bracket)
                    <strong class="dark:text-gray-200">{{ __('Pool') }} {{ $bracket->label }}</strong>
                    <ul>
                        @foreach($bracket->matches as $match)
                            @if(isset($match->team_1->label))
                                <li class="dark:text-gray-200">{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}
                                    vs
                                    {{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach
            </section>
        @endif

        @if($tournament->playoff)
            <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                <h1 class="text-xl font-bold dark:text-gray-200">{{ __('Playoff matches') }}</h1>
                <ul>
                    @foreach($tournament->playoff->matches as $match)
                        @if(isset($match->team_1->label))
                            <li class="dark:text-gray-200">{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}
                                vs
                                {{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</div>
