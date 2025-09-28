<div class="md:w-2/3 m-auto dark:text-gray-300">
    <div class="mt-4">
        <div>
            <h1 class="text-xl font-bold ">{{ __('Pools') }}</h1>
            <p><em class="">Cliquer sur une case pour enregistrer le score</em></p>
        </div>
        <a href="/tournaments/{{ $tournament->id }}/registration">
            <button
                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                < {{ __('Back') }}
            </button>
        </a>
        <button
            {{ $ready_to_generate ? "" : 'disabled' }}
            wire:click="generatePlayoff"
            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded
                        disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
            {{ __('Generate playoff') }}
        </button>

        <a href="/tournaments/{{ $tournament->id }}/playoff">
            <button {{ $playoff_generated ? '' : 'disabled' }}
                    class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded
                    disabled:cursor-not-allowed disabled:bg-gray-400 disabled:border-gray-400 disabled:text-white">
                {{ __('Next') }} >
            </button>
        </a>
    </div>
    <hr class="my-4">
    @foreach($tournament->brackets as $bracket)
        <br>
        <strong class="">Poule {{ $bracket->label }}</strong>
        <div class="flex items-center">
            @php
                $cpt = 0;
            @endphp
            @foreach($bracket->matches->chunk(2) as $chunk)
                <div>
                    @foreach($chunk as $match)
                        <livewire:match-card :$match key="{{ now() }}" label="{{ $this->matchLabels[$cpt++] }}">
                    @endforeach
                </div>
            @endforeach
            @if($bracket->results())
                <ul class=" m-4 text-center w-40">
                    <li class="border border-black dark:border-gray-300 border-solid p-2 rounded-t-xl">
                        <strong>{{ __('Leaderboard') }}</strong>
                    </li>
                    <x-result-item class="bg-green-800">
                        <p>1</p>
                        <p>{{ $bracket->results()[0]->label }}</p>
                    </x-result-item>
                    <x-result-item class="bg-green-800">
                        <p>2</p>
                        <p>{{ $bracket->results()[1]->label }}</p>
                    </x-result-item>
                    <x-result-item class="bg-red-800">
                        <p>3</p>
                        <p>{{ $bracket->results()[2]->label }}</p>
                    </x-result-item>
                    <x-result-item class="bg-red-800 rounded-b-xl">
                        <p>4</p>
                        <p>{{ $bracket->results()[3]->label }}</p>
                    </x-result-item>
                </ul>
            @endif
        </div>
    @endforeach

</div>
