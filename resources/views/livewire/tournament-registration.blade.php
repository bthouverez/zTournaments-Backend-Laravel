<div>
    <section class=" md:w-2/3 m-auto">
        <h1 class="text-2xl font-extrabold">{{ __('Registration') }} - {{ $tournament->label }} - {{ $tournament->team_size }}</h1>
        <a href="/tournaments">
            <button
                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Retour aux tournois
            </button>
        </a>
    </section>

    <div class="flex my-8 md:w-2/3 m-auto gap-2">
        <div class="border border-solid border-grey-800 border-2 md:p-8 p-2  rounded drop-shadow-sm">

            @if($tournament->teams->first())
                <button onclick="confirm('Sur ?') || event.stopImmediatePropagation()"
                        wire:click="resetTeams"
                        class="mb-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    {{ __('Delete teams') }}
                </button>
            @endif

            @if(!$tournament->matches->count())
                <h3 class="font-bold">{{ __('Match generation') }}</h3>
                <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}} {{ $tournament->teams->count() > 1 ? "" : 'disabled' }}
                        wire:click="generateBrackets(4)"
                        class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded
                        disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                    {{ __('Pools') }}

                </button>
                <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}} {{ $tournament->teams->count() > 1 ? "" : 'disabled' }}
                        wire:click="generatePlayoff"
                        class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded
                        disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                    {{ __('Playoff') }}

                </button>
                @if($tournament->team_size == 1)
                    <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}} {{ $tournament->teams->count() > 1 ? "" : 'disabled' }}
                            wire:click="generatePrecision"
                            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded
                            disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                        {{ __('Precision') }}
                    </button>
                @endif
                <br>
            @else
                <br>
                <button onclick="confirm('Sur ?')"
                        wire:click="resetMatches"
                        class="mb-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    {{ __('Delete matches') }}
                </button>
            @endif

            @if(!$tournament->matches->count())
                <h3 class="font-bold mt-2">{{ __('Add team') }}</h3>
                <div class="flex gap-2">
                    <div class="flex flex-col gap-2">
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

                </div>
                @if(count($tournament->teams))
                    <h3 class="font-bold mt-2">{{ __('Teams') }} ({{ $tournament->teams->count() }})</h3>
                    <div class="flex flex-wrap gap-4 md:m-4">
                    @foreach($tournament->teams as $team)
                        <div class="border border-gray-300 p-4">
                            <div class="flex">
                                <p class="text-lg font-bold p-2">{{ $team->label }}</p>
                                <button wire:click="removeTeam({{ $team->id }})"
                                        class="bg-transparent hover:bg-red-500 text-red-500 hover:text-white px-2 border border-2 border-red-500 hover:border-transparent rounded text-lg self-start">
                                    x
                                </button>
                            </div>

                            <hr class="border border-1 border-gray-500 mb-2">
                            @foreach($team->players as $player)
                                <p class="ml-3">{{ $player->name }}</p>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4">{{ __('No team registered') }}</p>
                @endif
            @endif




            @if($tournament->matches->count())
                <section class="">
                    <a href="/tournaments/{{ $tournament->id }}/{{ $nextStep }}">
                        <button
                            class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                            {{ __('Next') }} >
                        </button>
                </section>
            @endif
        </div>
            @if($tournament->brackets->count())
                <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                    <h1 class="text-xl font-bold">
                        {{ __('Pools') }} ({{ $tournament->brackets->count() }})
                    </h1>
                    <ul>
                        @php($cpt=0)
                        @foreach($tournament->brackets->sortBy('label') as $bracket)
                            <li><strong>{{ __('Pool') }} {{ $bracket->label }}, {{ __('Fields') }} {{ ++$cpt }} & {{ ++$cpt }}</strong>
                                @foreach($bracket->bracket_teams as $team)
                                    <ul>
                                        <li>{{ $team->label }}</li>
                                    </ul>
                            </li>
                        @endforeach
                        <br>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($tournament->brackets->count())
                <section class="border border-solid border-grey-800 border-2 md:p-8 rounded drop-shadow-sm">
                    <h1 class="text-xl font-bold">Les matchs du premier tour</h1>
                    @foreach($tournament->brackets as $bracket)
                        <strong>Poule {{ $bracket->label }}</strong>
                        <ul>
                            @foreach($bracket->matches as $match)
                                @if(isset($match->team_1->label))
                                    <li>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}
                                        vs
                                        {{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <br>
                    @endforeach
                </section>
            @endif
        </div>
    </div>
</div>
</div>
