<div class="md:w-2/3 m-auto">
    <div class="mt-4">
        <div>
            <h1 class="text-xl font-bold">{{ __('Pools') }}</h1>
            <p><em>Cliquer sur une case pour enregistrer le score</em></p>
        </div>
        <a href="/tournaments/{{ $tournament->id }}/registration">
            <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                < {{ __('Back') }}
            </button>
        </a>
        <hr class="my-4">
{{--        TODO --}}
        @if(!$tournament->brackets->map->matches->first()->map->isPlayed()->contains(false))
            <button
                wire:click="generatePlayoffFromBrackets"
                class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                Générer tournoi principal
            </button>
        @endif
    </div>

        @foreach($tournament->brackets as $bracket)
            <br>
            <strong class="">Poule {{ $bracket->label }}</strong>
            <div class="flex items-center">
                @php
                    $matchLabels = ['Premier tour', 'Premier tour', 'Match des gagnants', 'Match des perdants', 'Match de barrage'];
                    $cpt = 0;
                @endphp
                @foreach($bracket->matches->chunk(2) as $chunk)
                    <div>
                        @foreach($chunk as $match)
                            <div class="border border-4 border-solid p-2 m-4 hover:cursor-pointer w-64
                            @if($match->isPlayed())
                                bg-green-200
                            @elseif($match->team_1 AND $match->team_2)
                                bg-orange-200
                            @endif
                        {{ $match->id == $selected_match_id ? " border-orange-500 " : '' }}"
                                 wire:click="selectMatch({{ $match->id }})">
                                <p class="text-xs mb-2 ">
                                    {{ $matchLabels[$cpt++] }}
                                </p>
                                <p class="text-center">
                                    <strong>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}</strong>
                                    vs
                                    <strong>{{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }} </strong>
                                </p>
                                @if($selected_match_id == $match->id)
                                    @php
                                        $game = \App\Models\Game::find($selected_match_id);
                                        $endgame = $game->isPlayed();
                                    @endphp
                                    <div class="flex justify-center gap-2">
                                        <input type="number" class="w-14" max="13" min="0" wire:model.lazy="score_team_1"
                                              placeholder="{{ $game->team_1_score }}">
                                        <input wire:keydown.enter="setScore"
                                               type="number" class="w-14" max="13" min="0"
                                               wire:model.lazy="score_team_2"
                                               placeholder="{{ $game->team_2_score }}"
                                        @if($endgame)
                                            <button class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-1 px-2 border border-red-500 hover:border-transparent rounded"
                                                    wire:click="resetScore">
                                                Effacer
                                            </button>
                                        @else
                                            <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-1 px-2 border border-green-500 hover:border-transparent rounded"
                                                    wire:click="setScore">
                                                Valider
                                            </button>
                                        @endif
                                    </div>

                                @else
                                    <p class="text-lg font-bold  text-center">
                                        {{ $match->team_1_score ?: 0 }}
                                        -
                                        {{ $match->team_2_score ?: 0 }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
                @if($bracket->results())
                    <ul class="m-4 border border-solid border-black text-center">
                        <li class="border border-black border-solid"><strong class="px-2">Classement</strong></li>
                        <li class="border border-black border-solid p-2 bg-green-700">1
                            - {{ $bracket->results()[0]->label }}</li>
                        <li class="border border-black border-solid p-2 bg-green-700">2
                            - {{ $bracket->results()[1]->label }}</li>
                        <li class="border border-black border-solid p-2 bg-red-700">3
                            - {{ $bracket->results()[2]->label }}</li>
                        <li class="border border-black border-solid p-2 bg-red-700">4
                            - {{ $bracket->results()[3]->label }}</li>
                    </ul>
                @endif
            </div>
        @endforeach

</div>
