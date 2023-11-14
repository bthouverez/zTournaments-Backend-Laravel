<?php

namespace App\Livewire;

use App\Models\Tournament;
use Illuminate\Support\Collection;
use Livewire\Component;

class TournamentPlayoff extends Component
{

    public Tournament $tournament;
    public Collection $matches;
    protected array $matchLabels = [
        'Finale', 'Demi-finale', 'Quart de finale', 'Huitième de finale', 'Seizième de finale',
        'Trente-deuxième de finale'
    ];

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->matches = $tournament->playoff->matches;
    }


    public function render()
    {
        return view('livewire.tournament-playoff');
    }
}
