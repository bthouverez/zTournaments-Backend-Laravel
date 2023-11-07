<section class="flex items-stretch">
    <div>
        @php
            $cpt = intval(count($selected_playoff_matches)/2);
            $ii = 0;
            $matchLabels = ['Finale', 'Demi-finale', 'Quart de finale', 'Huitième de finale', 'Seizième de finale', 'Trente-deuxième de finale'];

            $jj = 1;
            $labelCpt = 0;
            while($jj <= $cpt) {
                $jj *= 2;
                $labelCpt++;
            }
        @endphp
        @foreach($selected_playoff_matches as $match)

            <div class="border border-4 border-solid p-2 m-2 hover:cursor-pointer w-64
                        {{ ($match->team_1 AND $match->team_2) ? "bg-orange-200 " : '' }}
                        {{ ($match->team_1_score == 13 OR $match->team_2_score == 13) ? "bg-green-200 " : '' }}
                        {{ $match->id == $selected_match_id ? " border-orange-500 " : '' }}
                        @if($match->team_1_id == $match->team_2_id AND $match->team_1_id AND $match->team_2_id)
                            invisible
                        @endif
                        "
                 {{--                         style="margin: 25px;"--}}
                 wire:click="selectMatch({{ $match }})">
                <p class="text-xs mb-1 ">
                    {{ $match->id }}
                    {{ $matchLabels[$labelCpt] }}
                    → {{ $match->winner_next_match_id }}
                </p>
                <p class="text-center">
                    <strong>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}</strong>
                    vs
                    <strong>{{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }} </strong>
                </p>
                <p class="text-lg font-bold  text-center">{{ $match->team_1_score ?: 0 }}
                    - {{ $match->team_2_score ?: 0 }} </p>
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
