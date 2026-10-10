<?php

namespace App\Livewire;

use App\Models\Tournament;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        abort_unless($tournament->hasAccess(Auth::user()), 403);

        $this->tournament = $tournament;
        $this->matches = $tournament->playoff->matches;
    }


    public function render()
    {
        return view('livewire.tournament-playoff');
    }
}
