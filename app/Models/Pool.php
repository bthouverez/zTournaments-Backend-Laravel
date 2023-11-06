<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    public function bracket_teams() {
        return $this->hasMany(Team::class, 'bracket_id')->orderBy('id');
    }


    public function playoff_teams() {
        return $this->hasMany(Team::class, 'playoff_id')->orderBy('id');
    }

    public function matches() {
        return $this->hasMany(Game::class)->orderBy('id');
    }

    public function precision_pool()
    {
        return $this->hasOne(PrecisionPool::class);
    }
}
