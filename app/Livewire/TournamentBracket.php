<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Collection;
use Livewire\Component;

class TournamentBracket extends Component
{

    public Tournament $tournament;
    public Collection $matches;
    protected array $matchLabels = [
        'Premier tour', 'Premier tour', 'Match des gagnants', 'Match des perdants', 'Match de barrage'
    ];

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->matches = Game::join('pools', 'pools.id', '=', 'games.pool_id')
            ->where('pools.tournament_id', '=', $tournament->id)
            ->where('pools.label', 'principal')
            ->select('games.*')
            ->orderBy('id')
            ->get();

    }


    public function render()
    {
        return view('livewire.tournament-bracket');
    }
}

