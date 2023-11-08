<section class="flex items-stretch">
    <div>
        @php
            $cpt = intval(count($this->matches)/2);
            $ii = 0;
            $matchLabels = ['Finale', 'Demi-finale', 'Quart de finale', 'Huitième de finale', 'Seizième de finale', 'Trente-deuxième de finale'];

            $jj = 1;
            $labelCpt = 0;
            while($jj <= $cpt) {
                $jj *= 2;
                $labelCpt++;
            }
        @endphp
        @foreach($this->matches as $match)

            <div class="border border-4 border-solid p-2 m-2 hover:cursor-pointer w-64
                            @if($match->isPlayed())
                                bg-green-200
                            @elseif($match->team_1 AND $match->team_2)
                                bg-orange-200
                            @endif
                        @if($match->team_1_id == $match->team_2_id AND $match->team_1_id AND $match->team_2_id)
                            invisible
                        @endif
                        "
                 {{--                         style="margin: 25px;"--}}
                 wire:click="selectMatch({{ $match->id }})">
                <p class="text-xs mb-1 ">
{{--                    {{ $match->id }}--}}
                    {{ $matchLabels[$labelCpt] }}
{{--                    → {{ $match->winner_next_match_id }}--}}
                </p>
                <p class="text-center">
                    <strong>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}</strong>
                    vs
                    <strong>{{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }} </strong>
                </p>@if($selected_match_id == $match->id)
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

            @if($ii++ == $cpt)
                @php
                    $cpt = intval($cpt/2);
                    $ii = 0;
                    $labelCpt--;
                @endphp
    </div>
    <div class="flex flex-col  justify-around">
        @endif
        @endforeach
    </div>
</section>
