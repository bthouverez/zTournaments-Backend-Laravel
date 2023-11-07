<?php

namespace App\Livewire;

use App\Models\PrecisionPool;
use App\Models\Tournament;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TournamentPrecision extends Component
{
    public array $ACTIVITIES = ['', 'Boule seule', 'Boule derrière but', 'Trois boules', 'Boule derrière boule', 'But'];
    public Collection $teams;
    public PrecisionPool $pool;
    public int $current_activity; // 1 à 5
    public int $current_player_index; // 0 a nbJoueurs
    public int $current_distance; // 6, 7, 8, 9

    public function mount(Tournament $tournament)
    {
        $this->teams = $tournament->teams->sortBy('created_at')->values();
        $this->pool = $tournament->precision->first()->precision_pool;
    }

    public function setScore(int $score)
    {
        $team = $this->teams->get($this->pool->current_player_index);
        $team->score += $score;
        $team->save();
        $this->nextStep();
    }

    public function nextStep()
    {
        $this->pool->current_player_index++;
        if($this->pool->current_player_index == $this->teams->count()) {
            $this->pool->current_player_index = 0;
            $this->pool->current_distance++;
            if($this->pool->current_distance == 10) {
                $this->pool->current_distance = 6;
                $this->pool->current_activity++;
            }
        }

        $this->pool->save();
    }

    public function render()
    {
        return view('livewire.tournament-precision');
    }
}
