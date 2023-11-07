<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Pool;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;

class TournamentBracket extends Component
{
    public $tournament;
    public $selected_match_id;
    public $score_team_1;
    public $score_team_2;

    public $matches;


    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->selected_match_id = 0;

        $this->matches = Game::join('pools', 'pools.id', '=', 'games.pool_id')
            ->where('pools.tournament_id', '=', $tournament->id)
            ->where('pools.label', 'principal')
            ->select('games.*')
            ->orderBy('id')
            ->get();

    }

    public function selectMatch($match)
    {
        if ($match['team_1'] and $match['team_2'])
            $this->selected_match_id = $match['id'];
        else $this->selected_match_id = 0;
    }

    public function setScore()
    {
        $match = Game::find($this->selected_match_id);
        $match->team_1_score = $this->score_team_1;
        $match->team_2_score = $this->score_team_2;
        $match->save();
        $winner_id = $this->score_team_1 == 13 ? $match->team_1_id : $match->team_2_id;
        $loser_id = $this->score_team_1 == 13 ? $match->team_2_id : $match->team_1_id;
        $this->selected_match_id = 0;

        // MEt à jour les équipes des matchs suivants
        $winner_match = $match->winner_next_match;
        if ($winner_match) {
            if ($winner_match->team_1) $winner_match->team_2_id = $winner_id;
            else $winner_match->team_1_id = $winner_id;
            $winner_match->save();
        }

        $loser_match = $match->loser_next_match;
        if ($loser_match) {
            if ($loser_match->team_1) $loser_match->team_2_id = $loser_id;
            else $loser_match->team_1_id = $loser_id;
            $loser_match->save();
        }
    }

    public function resetScore()
    {
        $match = Game::find($this->selected_match_id);
        $winner_id = $match->team_1_score == 13 ? $match->team_1_id : $match->team_2_id;
        $loser_id = $match->team_1_score == 13 ? $match->team_2_id : $match->team_1_id;

        $next_match_winner = $match->winner_next_match;
        $next_match_loser = $match->loser_next_match;
        if ($next_match_winner) {
            if ($next_match_winner->team_1_id == $winner_id) {
                $next_match_winner->team_1_id = $next_match_winner->team_2_id;
            }
            $next_match_winner->team_2_id = null;
            $next_match_winner->team_1_score = 0;
            $next_match_winner->team_2_score = 0;
            $next_match_winner->save();
        }

        if ($next_match_loser) {
            if ($next_match_loser->team_1_id == $loser_id) {
                $next_match_loser->team_1_id = $next_match_loser->team_2_id;
            }
            $next_match_loser->team_2_id = null;
            $next_match_loser->team_1_score = 0;
            $next_match_loser->team_2_score = 0;
            $next_match_loser->save();
        }

        $match->team_1_score = 0;
        $match->team_2_score = 0;
        $match->save();
    }






    public function render()
    {
        return view('livewire.tournament-bracket');
    }
}

