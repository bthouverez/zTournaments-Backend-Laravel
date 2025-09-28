<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class TournamentBracket extends Component
{

    public Tournament $tournament;
    public Collection $matches;
    public bool $ready_to_generate = false;
    public bool $playoff_generated = false;
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
        $this->checkBracketsEnd();

    }

    #[On('check-brackets-end')]
    public function checkBracketsEnd()
    {
        if($this->tournament->playoff) {
            $this->playoff_generated = true;
            $this->ready_to_generate = false;
        } else {
            $this->ready_to_generate = true;
            foreach ($this->tournament->matches as $match) {
                if ($match->team_2_id == null) {
                    $this->ready_to_generate = false;
                    break;
                }
            }
        }
    }

    public function generatePlayoff()
    {
//        foreach($this->tournament->brackets as $bracket) {
//            $this->tournament->teams->delete($bracket->results()[2]);
//            $this->tournament->teams->delete($bracket->results()[3]);
//        }
        $teams = [];
        foreach($this->tournament->brackets as $bracket) {
            $teams[] = $bracket->results()[0];
            $teams[] = $bracket->results()[1];
        }
//        $this->tournament->save();
        $this->tournament->generatePlayoffMatches($teams);
        $this->playoff_generated = true;
        $this->ready_to_generate = false;
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.tournament-bracket');
    }
}

