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
            $team->label = $this->tournament->team_size <= 1 ? $p1 : __('Team') . ' ' . ++$this->teamsCount;
//            $team->label = __('Team') . ' ' . ++$this->teamsCount;
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
            $this->tournament->generatePrecision();
        } else {
            if ($this->tournament->has_brackets) {
                $this->tournament->generateBrackets(4);
            } else {
                $this->tournament->generatePlayoff();
            }
        }
    }


    public function removeTeam(int $id)
    {
        Player::where('team_id', $id)->delete();
        Team::find($id)->delete();
    }

    public function resetTeams()
    {
        $this->tournament->resetTeams();
    }

    public function resetMatches()
    {
        $this->tournament->resetMatches();

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
