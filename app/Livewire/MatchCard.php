<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Team;
use Livewire\Component;

class MatchCard extends Component
{
    public Game $match;
    public int $selectedMatchId;
    public string $label;
    public $score_team_1;
    public $score_team_2;

    public function mount($match, $label)
    {
        $this->match = $match;
        $this->selectedMatchId = 0;
        $this->label = $label;
    }


    public function selectMatch(Game $match)
    {
        $this->selectedMatchId = 0;
        if ($match->team_1 and $match->team_2) {
            $this->selectedMatchId = $match->id;
        }
    }

    public function setScore(Game $match)
    {
        $match->team_1_score = $this->score_team_1;
        $match->team_2_score = $this->score_team_2;
        $match->save();
        $winner_id = $this->score_team_1 == 13 ? $match->team_1_id : $match->team_2_id;
        $loser_id = $this->score_team_1 == 13 ? $match->team_2_id : $match->team_1_id;
        $winner = Team::find($winner_id);
        $winner->score += 2;
        $winner->save();
        $loser = Team::find($loser_id);
        $loser->score -= 1;
        $loser->save();

        $this->selectedMatchId = 0;

        // MEt à jour les équipes des matchs suivants
        $winner_match = $match->winner_next_match;
        if ($winner_match) {
            if ($winner_match->team_1) {
                $winner_match->team_2_id = $winner_id;
            } else {
                $winner_match->team_1_id = $winner_id;
            }
            $winner_match->save();
        }

        $loser_match = $match->loser_next_match;
        if ($loser_match) {
            if ($loser_match->team_1) {
                $loser_match->team_2_id = $loser_id;
            } else {
                $loser_match->team_1_id = $loser_id;
            }
            $loser_match->save();
        }
    }

    public function render()
    {
        return view('livewire.match-card');
    }

    public function resetScore()
    {
        $match = Game::find($this->selectedMatchId);
        $winner_id = $match->team_1_score == 13 ? $match->team_1_id : $match->team_2_id;
        $loser_id = $match->team_1_score == 13 ? $match->team_2_id : $match->team_1_id;
        $winner = Team::find($winner_id);
        $winner->score -= 2;
        $winner->save();
        $loser = Team::find($loser_id);
        $loser->score += 1;
        $loser->save();

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

}
