<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use App\Models\Pool;
use App\Models\PrecisionPool;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TournamentRegistration extends Component
{
    use WithFileUploads;

    public Tournament $tournament;
    public string $newPlayer1;
    public string $newPlayer2;
    public string $newPlayer3;
    public int $teamsCount;
    public string $nextStep;

    public $playersListFile;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->newPlayer1 = "";
        $this->newPlayer2 = "";
        $this->newPlayer3 = "";
        $this->playersListFile = "";
        if ($tournament->team_size == 0) {
            $this->nextStep = "precision";
        } else {
            if ($tournament->has_brackets) {
                $this->nextStep = "bracket";
            } else {
                $this->nextStep = "playoff";
            }
        }
        $this->teamsCount = $this->tournament->teams->count();
    }


    public function addTeam()
    {
        $player1 = null;
        $player2 = null;
        $player3 = null;

        // Ajoute le joueur 1
        if ($this->newPlayer1) {
            $player1 = new Player();
            $player1->name = ucwords($this->newPlayer1);
        }

        // Ajoute le joueur 2
        if ($this->newPlayer2) {
            $player2 = new Player();
            $player2->name = ucwords($this->newPlayer2);
        }

        // Ajoute le joueur 3
        if ($this->newPlayer3) {
            $player3 = new Player();
            $player3->name = ucwords($this->newPlayer3);
        }

        // Crée l'équipe
        $team = new Team();
        $team->label = $this->tournament->team_size <= 1 ? $player1->name : __('Team') . ' ' . ++$this->teamsCount;
        $team->number = $this->teamsCount;
        $team->tournament_id = $this->tournament->id;
        $team->save();

        if ($player1) {
            $player1->team_id = $team->id;
            $player1->save();
        }

        if ($player2) {
            $player2->team_id = $team->id;
            $player2->save();
        }

        if ($player3) {
            $player3->team_id = $team->id;
            $player3->save();
        }

        $this->newPlayer1 = "";
        $this->newPlayer2 = "";
        $this->newPlayer3 = "";
        $this->newTeam = "";
    }


    public function loadPlayersFile() : void
    {
        $this->playersListFile->storeAs('public', 'tmpTab.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('storage/tmpTab.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $this->teamsCount = 0;
        $nLigne = 2;
        while (true) {
            $p1 = $sheet->getCell('B' . $nLigne)->getValue();
            $p2 = $sheet->getCell('C' . $nLigne)->getValue();
            $p3 = $sheet->getCell('D'.$nLigne)->getValue();

            if ($p1 == null) break;

            // Crée l'équipe
            $team = new Team();
//            $team->label = $this->tournament->team_size <= 1 ? $player1->name : __('Team') . ' ' . ++$this->teamsCount;
            $team->label = __('Team') . ' ' . ++$this->teamsCount;
            $team->number = $this->teamsCount;
            $team->tournament_id = $this->tournament->id;
            $team->save();

            $player1 = new Player();
            $player1->name = ucwords($p1);
            $player1->team_id = $team->id;
            $player1->save();

            if($this->tournament->team_size > 1) {
                $player2 = new Player();
                $player2->name = ucwords($p2);
                $player2->team_id = $team->id;
                $player2->save();
            }
            if($this->tournament->team_size > 2) {
                $player3 = new Player();
                $player3->name = ucwords($p3);
                $player3->team_id = $team->id;
                $player3->save();
            }
            $nLigne++;
        }
        File::delete('storage/tmpTab.xlsx');
    }

    public function generate()
    {
        if ($this->tournament->team_size == 0) {
            $this->generatePrecision();
        } else {
            if ($this->tournament->has_brackets) {
                $this->generateBrackets(4);
            } else {
                $this->generatePlayoff();
            }
        }
    }

    public function generatePrecision()
    {
        if (!$this->tournament->precision->count()) {
            $p = new Pool;
            $p->tournament_id = $this->tournament->id;
            $p->label = 'precision';
            $p->type = 3;
            $p->save();
            $pp = new PrecisionPool;
            $pp->pool_id = $p->id;
            $pp->save();
        }
        return redirect('/tournaments/' . $this->tournament->id . '/precision');
    }

    public function generateBrackets($size)
    {
        $this->resetMatches();

        // Chunk les équipes
        $bracketsChunks = $this->tournament->teams->shuffle()->chunk($size);
        $lastChunkId = count($bracketsChunks) - 1;
        $nbMissingTeams = $size - count($bracketsChunks[$lastChunkId]);
        // Réarrange les chunks si pas un multiple de n
        if ($nbMissingTeams != 0) {
            // Crée et ajoute un EXEMPT
            $team = new Team();
            $team->label = "Exempt 1";
            $team->tournament_id = $this->tournament->id;
            $team->save();
            $bracketsChunks[$lastChunkId][] = $team;
            $nbMissingTeams--;
            for ($ii = 0; $ii < $nbMissingTeams; $ii++) {
                $team = new Team();
                $team->label = "Exempt " . $ii + 2;
                $team->tournament_id = $this->tournament->id;
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
            $bracket->tournament_id = $this->tournament->id;
            $bracket->type = 1;
            $bracket->save();

            // Met à jour le bracket des équipes
            foreach ($rawBracket as $team) {
                $team->bracket_id = $bracket->id;
                $team->save();
            }

            // Premier tour
            $m = new Game();
            $m->tournament_id = $this->tournament->id;
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
            $m->tournament_id = $this->tournament->id;
            $m->pool_id = $bracket->id;
            $m->team_1_id = $rawBracket->get($chunksCpt++)->id;
            $m->team_2_id = $rawBracket->get($chunksCpt++)->id;
            $m->field = $fieldsCpt+1;
            $m->winner_next_match_id = $start_id + 2;
            $m->loser_next_match_id = $start_id + 3;
            $m->save();

            // Winners match
            $m = new Game();
            $m->tournament_id = $this->tournament->id;
            $m->pool_id = $bracket->id;
            $m->winner_next_match_id = 0;
            $m->loser_next_match_id = $start_id + 4;
            $m->field = $fieldsCpt;
            $m->save();

            // Losers match
            $m = new Game();
            $m->tournament_id = $this->tournament->id;
            $m->pool_id = $bracket->id;
            $m->winner_next_match_id = $start_id + 4;
            $m->loser_next_match_id = 0;
            $m->field = $fieldsCpt+1;
            $m->save();

            // Decider match
            $m = new Game();
            $m->tournament_id = $this->tournament->id;
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
        Game::where('tournament_id', $this->tournament->id)->delete();
//        $t_pools = Pool::where('tournament_id', $this->tournament->id)->get()->map->id->toArray();
//        PrecisionPool::whereIn('pool_id', $t_pools)->delete();
        Pool::where('tournament_id', $this->tournament->id)->delete();
    }

    public function generatePlayoff()
    {
        $teamsArray = [];
        foreach ($this->tournament->teams->shuffle() as $team) {
            $teamsArray[] = $team;
        }
        $this->generatePlayoffMatches($teamsArray, 'principal');
    }

    public function generatePlayoffMatches($teams, $name = 'tournoi')
    {

        // Crée la poule playoff
        $playoff = new Pool();
        $playoff->label = $name;
        $playoff->tournament_id = $this->tournament->id;
        $playoff->type = 2;
        $playoff->save();


        // TODO réparer ce truc crado
        $g = new Game();
        $g->tournament_id = $this->tournament->id;
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
                $match->tournament_id = $this->tournament->id;
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
                $match->tournament_id = $this->tournament->id;
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
                $match->tournament_id = $this->tournament->id;
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
        $match->tournament_id = $this->tournament->id;
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

    public function removeTeam(int $id)
    {
        Player::where('team_id', $id)->delete();
        Team::find($id)->delete();
    }

    public function resetTeams()
    {
        $this->resetMatches();
        // Supprime les joueurs des poules du tournoi
        $t_teams = Team::where('tournament_id', $this->tournament->id)->get()->map->id->toArray();
        Player::whereIn('team_id', $t_teams)->delete();
        // Supprime les équipes du tournoi
        Team::where('tournament_id', $this->tournament->id)->delete();
        $this->teamsCount = 0;
    }

    public function generatePlayoffFromBrackets()
    {
        $data = [];
        // Récupère les joueurs de chaque poule et les met dans un tableau
        // Le tableau contient 4 cases, la première contient un tableau de tous les premiers de poules
        // La deuxième contient un tableau de tous les seconds de poules etc...
        foreach ($this->tournament->brackets->map->results() as $result) {
            for ($ii = 0; $ii < 4; $ii++) {
                $data[$ii][] = $result[$ii];
            }
        }
        // Mélange les 4 sous-tableaux
        for ($ii = 0; $ii < 4; $ii++) {
            shuffle($data[$ii]);
        }

        // Insère les premiers et seconds dans le principal
        $tournoi_principal = [];
        for ($ii = 0; $ii < $this->tournament->brackets->count(); $ii++) {
            $tournoi_principal[] = array_pop($data[0]);
            $tournoi_principal[] = array_pop($data[1]);
        }

        $this->generatePlayoffMatches($tournoi_principal, 'principal');
    }

    public function render()
    {
        return view('livewire.tournament-registration');
    }
}
