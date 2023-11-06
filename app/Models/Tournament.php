<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function teams() {
        return $this->hasMany(Team::class, 'tournament_id')->orderBy('label');
    }

    public function players() {
        return $this->hasMany(Player::class, 'tournament_id')->orderBy('id');
    }

    public function matches() {
        return $this->hasMany(Game::class, 'tournament_id')->orderBy('id');
    }

    public function brackets() {
        return $this->hasMany(Pool::class, 'tournament_id')->where('type', 1)->orderBy('id');
    }

    public function playoffs() {
        return $this->hasMany(Pool::class, 'tournament_id')->where('type', 2)->orderBy('id');
    }

    public function precision() {
        return $this->hasMany(Pool::class, 'tournament_id')->where('type', 3)->orderBy('id');
    }
}
