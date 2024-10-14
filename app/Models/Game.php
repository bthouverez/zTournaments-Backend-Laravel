<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;


    public function teams() {
        return $this->belongsTo(Team::class, 'team_1_id')->union($this->belongsTo(Team::class, 'team_2_id'));
    }

    public function team_1() {
        return $this->belongsTo(Team::class, 'team_1_id');
    }

    public function team_2() {
        return $this->belongsTo(Team::class, 'team_2_id');
    }

    public function winner_next_match() {
        return $this->belongsTo(Game::class, 'winner_next_match_id');
    }

    public function loser_next_match() {
        return $this->belongsTo(Game::class, 'loser_next_match_id');
    }

    public function pool() {
        return $this->belongsTo(Pool::class);
    }

    public function isPLayed() {
        return $this->team_1_score != 0 OR $this->team_2_score != 0;
    }
}
