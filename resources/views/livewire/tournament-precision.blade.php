<div class="md:w-96 m-auto dark:text-gray-200">
    <a href="/Tir-de-precision2020.pdf">Règlement</a>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    @if($pool->current_activity < 6)
        <div class="border border-gray-400 border-2 rounded p-4 mt-4">
            <div class="text-center">
                <p>{{ $ACTIVITIES[$pool->current_activity] }}</p>
                <p>{{ $pool->current_distance }} m</p>
                <p class="text-lg font-bold my-2">{{ $teams->get($pool->current_player_index)->label }}</p>
            </div>
            <div class="flex gap-2 justify-center">
                <button wire:click="setScore(0)"
                        class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white
                    py-4 px-6 border border-orange-500 hover:border-transparent rounded">0
                </button>
                @if($pool->current_activity != 5)
                    <button wire:click="setScore(1)"
                            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white
                        py-4 px-6 border border-orange-500 hover:border-transparent rounded">1
                    </button>
                @endif
                <button wire:click="setScore(3)"
                        class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white
                    py-4 px-6 border border-orange-500 hover:border-transparent rounded">3
                </button>
                <button wire:click="setScore(5)"
                        class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white
                    py-4 px-6 border border-orange-500 hover:border-transparent rounded">5
                </button>
            </div>
        </div>
    @endif

    <div class="border border-gray-400 border-2 rounded p-4 mt-4">
        <h1 class="text-lg font-bold">{{ __('Leaderboard') }}</h1>
        @foreach($teams->sortByDesc('score') as $team)
            <p class="border-t border-gray-400 py-2">{{ $team->label }} - {{ $team->score }}</p>
        @endforeach
    </div>

    @if($pool->current_activity < 6)
        <div class="mt-4">
            <img src="/score_activity_{{ $pool->current_activity }}.png"
                 alt="score activity {{ $pool->current_activity }}"/>
        </div>
    @endif
</div>
