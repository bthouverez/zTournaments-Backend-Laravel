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

    public function playoff() {
        return $this->hasOne(Pool::class, 'tournament_id')->where('type', 2)->orderBy('id');
    }

    public function precision() {
        return $this->hasMany(Pool::class, 'tournament_id')->where('type', 3)->orderBy('id');
    }



    public function generatePrecision()
    {
        if (!$this->precision->count()) {
            $p = new Pool;
            $p->tournament_id = $this->id;
            $p->label = 'precision';
            $p->type = 3;
            $p->save();
            $pp = new PrecisionPool;
            $pp->pool_id = $p->id;
            $pp->save();
        }
        return redirect('/tournaments/' . $this->id . '/precision');
    }

    public function generateBrackets($size)
    {
        $this->resetMatches();

        // Chunk les équipes
        $bracketsChunks = $this->teams->shuffle()->chunk($size);
        $lastChunkId = count($bracketsChunks) - 1;
        $nbMissingTeams = $size - count($bracketsChunks[$lastChunkId]);
        // Réarrange les chunks si pas un multiple de n
        if ($nbMissingTeams != 0) {
            // Crée et ajoute un EXEMPT
            $team = new Team();
            $team->label = "Exempt 1";
            $team->tournament_id = $this->id;
            $team->save();
            $bracketsChunks[$lastChunkId][] = $team;
            $nbMissingTeams--;
            for ($ii = 0; $ii < $nbMissingTeams; $ii++) {
                $team = new Team();
                $team->label = "Exempt " . $ii + 2;
                $team->tournament_id = $this->id;
                $team->save();
                $bracketsChunks[$lastChunkId][] = $bracketsChunks[$ii]->pop();
                $bracketsChunks[$ii][] = $team;
            }
        }

        $chunksCpt = 0;
        $fieldsCpt = 1;
        foreach ($bracketsChunks as $ii => $rawBracket) {
            // Crée un bracket par chunk
            $bracket = new Pool();
            $bracket->label = chr($ii + 65);
            $bracket->tournament_id = $this->id;
            $bracket->type = 1;
            $bracket->save();

            // Met à jour le bracket des équipes
            foreach ($rawBracket as $team) {
                $team->bracket_id = $bracket->id;
                $team->save();
            }

            // Premier tour
            $m = new Game();
            $m->tournament_id = $this->id;
            $m->pool_id = $bracket->id;
            $m->team_1_id = $rawBracket->get($chunksCpt++)->id;
            $m->team_2_id = $rawBracket->get($chunksCpt++)->id;
            $m->field = $fieldsCpt;
            $m->save();
            $start_id = $m->id;
            $m->winner_next_match_id = $start_id + 2;
            $m->loser_next_match_id = $start_id + 3;
            $m->save();

            // Premier tour
            $m = new Game();
            $m->tournament_id = $this->id;
            $m->pool_id = $bracket->id;
            $m->team_1_id = $rawBracket->get($chunksCpt++)->id;
            $m->team_2_id = $rawBracket->get($chunksCpt++)->id;
            $m->field = $fieldsCpt+1;
            $m->winner_next_match_id = $start_id + 2;
            $m->loser_next_match_id = $start_id + 3;
            $m->save();

            // Winners match
            $m = new Game();
            $m->tournament_id = $this->id;
            $m->pool_id = $bracket->id;
            $m->winner_next_match_id = 0;
            $m->loser_next_match_id = $start_id + 4;
            $m->field = $fieldsCpt;
            $m->save();

            // Losers match
            $m = new Game();
            $m->tournament_id = $this->id;
            $m->pool_id = $bracket->id;
            $m->winner_next_match_id = $start_id + 4;
            $m->loser_next_match_id = 0;
            $m->field = $fieldsCpt+1;
            $m->save();

            // Decider match
            $m = new Game();
            $m->tournament_id = $this->id;
            $m->pool_id = $bracket->id;
            $m->winner_next_match_id = 0;
            $m->loser_next_match_id = 0;
            $m->field = $fieldsCpt;
            $m->save();

            $fieldsCpt += 2;
        }
    }

    public function resetMatches()
    {
        Game::where('tournament_id', $this->id)->delete();
//        $t_pools = Pool::where('tournament_id', $this->id)->get()->map->id->toArray();
//        PrecisionPool::whereIn('pool_id', $t_pools)->delete();
        Pool::where('tournament_id', $this->id)->delete();
    }

    public function resetTeams()
    {
        $this->resetMatches();
        // Supprime les joueurs des poules du tournoi
        $t_teams = Team::where('tournament_id', $this->id)->get()->map->id->toArray();
        Player::whereIn('team_id', $t_teams)->delete();
        // Supprime les équipes du tournoi
        Team::where('tournament_id', $this->id)->delete();
        $this->teamsCount = 0;
    }

    public function generatePlayoff()
    {
        $teamsArray = [];
        foreach ($this->teams->shuffle() as $team) {
            $teamsArray[] = $team;
        }
        $this->generatePlayoffMatches($teamsArray, 'principal');
    }

    public function generatePlayoffMatches($teams, $name = 'tournoi')
    {

        // Crée la poule playoff
        $playoff = new Pool();
        $playoff->label = $name;
        $playoff->tournament_id = $this->id;
        $playoff->type = 2;
        $playoff->save();


        // TODO réparer ce truc crado
        $g = new Game();
        $g->tournament_id = $this->id;
        $g->pool_id = 1;
        $g->save();
        $match_start_id = $g->id + 1;
        $g->delete();

        $next_matches = [];
        $same_team_matches = [];

        // Vérifie le nombre d'équipes, puissance de 2 ou non
        $cpt = 1;
        while ($cpt <= count($teams)) {
            $cpt *= 2;
        }
        $cpt /= 2;
        $chunks = array_chunk($teams, $cpt);


        /// PREMIER TOUR, AVEC EQUIPES
        // Si le nombre d'équipes n'est pas une puissance de 2
        if (isset($chunks[1])) {
            for ($ii = 0; $ii < $cpt; $ii++) {
                $match = new Game();
                $match->tournament_id = $this->id;
                $match->pool_id = $playoff->id;
                $match->team_1_id = $chunks[0][$ii]->id;
                // Si le second chunk contient toujours une valeur, crée le match avec l'équipe correspondante
                // Sinon crée un match avec la même équipe
                if (isset($chunks[1][$ii])) {
                    $match->team_2_id = $chunks[1][$ii]->id;
                } else {
                    $match->team_2_id = $chunks[0][$ii]->id;
                    $same_team_matches[] = $match;
                }
                $match->loser_next_match_id = 0;
                $match->winner_next_match_id = $match_start_id + $cpt + floor(($ii) / 2);
                if (!in_array($match->winner_next_match_id, $next_matches)) {
                    $next_matches[] = $match->winner_next_match_id;
                }
                $match->save();
            }


        } else {

            foreach (array_chunk($teams, 2) as $ii => $chunk) {
                $match = new Game();
                $match->tournament_id = $this->id;
                $match->pool_id = $playoff->id;
                $match->team_1_id = $chunk[0]->id;
                $match->team_2_id = $chunk[1]->id;
                $match->loser_next_match_id = 0;
                $match->winner_next_match_id = $match_start_id + intval(count($teams) / 2 + floor(($ii) / 2));
                if (!in_array($match->winner_next_match_id, $next_matches)) {
                    $next_matches[] = $match->winner_next_match_id;
                }
                $match->save();
            }
        }

        /// AUTRE TOURS, EQUIPES INDEFINIES
        while (count($next_matches) > 1) {
            $data = [];
            $match_start_id = $next_matches[0];
            foreach ($next_matches as $ii => $match_id) {
                $match = new Game();
                $match->tournament_id = $this->id;
                $match->pool_id = $playoff->id;
                $match->loser_next_match_id = 0;
                $match->winner_next_match_id = $match_start_id + intval(count($next_matches) + floor(($ii) / 2));
                if (!in_array($match->winner_next_match_id, $data)) {
                    $data[] = $match->winner_next_match_id;
                }
                $match->save();
            }
            $next_matches = $data;
        }
        $match = new Game();
        $match->tournament_id = $this->id;
        $match->pool_id = $playoff->id;
        $match->winner_next_match_id = 0;
        $match->loser_next_match_id = 0;
        $match->save();
        // MISE A JOUR DES MATCHES QUAND PAS UNE PUISSANCE DE 2
        foreach ($same_team_matches as $match) {
            $next_match = $match->winner_next_match;
            if ($next_match->team_1_id) {
                $next_match->team_2_id = $match->team_1_id;
            } else {
                $next_match->team_1_id = $match->team_1_id;
            }
            $match->save();
            $next_match->save();
        }
    }
}
