<div class="border border-4 border-solid p-2 m-4 hover:cursor-pointer w-64
        @if($match->isPlayed())
            bg-green-200 dark:bg-green-800
        @elseif($match->team_1 AND $match->team_2)
            bg-orange-200 dark:bg-amber-800
        @endif
        @if($match->id == $selectedMatchId)
            border-orange-500
        @endif
        @if($match->team_1_id == $match->team_2_id AND $match->team_1_id AND $match->team_2_id)
            invisible
        @endif
     "
     wire:click="selectMatch({{ $match }})">
    <p class="text-xs mb-2 dark:text-gray-300">
        {{ $label }}
    </p>
    <p class="text-center dark:text-gray-300">
        <strong
            class="dark:text-gray-300">{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}</strong>
        vs
        <strong
            class="dark:text-gray-300">{{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }} </strong>
    </p>
    @if($selectedMatchId == $match->id)
        <div class="flex justify-center gap-2 h-8">
            <form wire:submit="setScore({{ $match }})">
                <x-input type="number" class="w-14 h-8" max="13" min="0"
                         wire:model.lazy="score_team_1"
                         placeholder="{{ $match->team_1_score }}"></x-input>
                <x-input wire:keydown.enter="setScore"
                         type="number" class="w-14 h-8" max="13" min="0"
                         wire:model.lazy="score_team_2"
                         placeholder="{{ $match->team_2_score }}"></x-input>
                @if($match->isPlayed())
                    <x-button
                        type="button"
                        class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-1 px-2 border border-red-500 hover:border-transparent rounded"
                        wire:click="resetScore">
                        Effacer
                    </x-button>
                @else
                    <x-button
                        class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-1 px-2 border border-green-500 hover:border-transparent rounded"
                    >
                        Valider
                    </x-button>
                @endif
            </form>
        </div>

    @else
        <p class="text-lg font-bold  text-center dark:text-gray-300 h-8">
            {{ $match->team_1_score ?: 0 }}
            -
            {{ $match->team_2_score ?: 0 }}
        </p>
    @endif
</div>

