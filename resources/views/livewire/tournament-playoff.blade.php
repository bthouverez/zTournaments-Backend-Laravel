<div>
    <div class="md:w-2/3 m-auto mt-4 ">
        <div>
            <h1 class="text-xl font-bold ">{{ __('Playoff') }}</h1>
            <p><em class="">Cliquer sur une case pour enregistrer le score</em></p>
        </div>
        <a href="/tournaments/{{ $tournament->id }}/{{ $tournament->has_bracket ? 'bracket' : 'registration' }}">
            <button
                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                < {{ __('Back') }}
            </button>
        </a>
    </div>
    <section class="flex items-stretch">
        <div>
            @php
                $cpt = intval(count($this->matches)/2);
                $ii = 0;

                $jj = 1;
                $labelCpt = 0;
                while($jj <= $cpt) {
                    $jj *= 2;
                    $labelCpt++;
                }
            @endphp
            @foreach($this->matches as $match)
                <livewire:match-card :$match
                                     label="{{ $this->matchLabels[$labelCpt] }}"/>
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

</div>
