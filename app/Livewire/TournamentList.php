<?php

namespace App\Livewire;

use App\Models\Tournament;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TournamentList extends Component
{
    public Collection $tournaments;
    public Collection $sharedTournaments;
    public string $label;
    public string $date;
    public string $place;
    public string $team_size;
    public int $has_brackets;
    public bool $melee = false;
    public string $joinCode = "";

    public function mount()
    {
        $this->tournaments = Auth::user()->tournaments;
        $this->sharedTournaments = Auth::user()->sharedTournaments;
        $this->label = "";
        $this->date = date('Y-m-d');
        $this->place = "";
        $this->team_size = -1;
    }

    public function setTeamSize(int $n)
    {
        $this->team_size = $n;
    }

    public function setHasBracket(int $n)
    {
        $this->has_brackets = $n;
    }

    public function createTournament()
    {
        $this->validate([
            'team_size' => 'required',
            'has_brackets' => 'required',
            'date' => 'required',
            'label' => 'required|min:3',
            'place' => 'required|min:3',
        ]);

        $t = new Tournament;
        $t->label = $this->label;
        $t->place = $this->place;
        $t->date = $this->date;
        $t->team_size = $this->team_size;
        $t->has_brackets = $this->has_brackets;
        $t->user_id = Auth::user()->id;
        $t->melee = $this->melee;
        $t->save();

        return redirect('/tournaments/'.$t->id.'/registration');
    }

    public function joinTournament()
    {
        $this->validate(['joinCode' => 'required|min:6|max:6']);

        $tournament = Tournament::joinByCode($this->joinCode, Auth::user());

        if (!$tournament) {
            $this->addError('joinCode', __('No tournament matches this code'));
            return;
        }

        return redirect('/tournaments/' . $tournament->id . '/registration');
    }

    public function delete($id = 0)
    {
        $t = Tournament::find($id);
        if ($t && $t->hasAccess(Auth::user())) {
            $t->resetTeams();
            $t->delete();
            $this->tournaments = Auth::user()->tournaments;
            $this->sharedTournaments = Auth::user()->sharedTournaments;
        }
    }
    public function render()
    {
        return view('livewire.tournament-list');
    }
}
