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
