<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function players() {
        return $this->hasMany(Player::class, 'team_id');
    }

    public function matches() {
        return $this->hasMany(Game::class, 'team_2_id')->union($this->hasMany(Game::class, 'team_1_id'));
    }

    public function bracket() {
        return $this->belongsTo(Pool::class, 'bracket_id');
    }

    public function playoff() {
        return $this->belongsTo(Pool::class, 'playoff_id');
    }
}
