<?php

namespace App\Livewire;

use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TournamentList extends Component
{
    public $tournaments;
    public $label;
    public $date;
    public $place;

    public function mount()
    {
        $this->tournaments = Tournament::all();
        $this->label = "";
        $this->date = date('Y-m-d');
        $this->place = "";
    }

    public function createTournament()
    {
        $t = new Tournament;
        $t->label = $this->label;
        $t->place = $this->place;
        $t->date = $this->date;
        $t->user_id = Auth::user()->id;
        $t->save();
        return redirect('/tournaments/'.$t->id.'/registration');
    }
    public function render()
    {
        return <<<'HTML'
            <div class="w-full max-w-xs mx-auto">
              <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-xl font-bold">{{ __('Create new tournament') }}</h1>
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="label">
                    {{ __('Label') }}
                  </label>
                  <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   wire:model="label" name="label" id="label" type="text" placeholder="{{ __('Label') }}">
                </div>
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="date">
                    {{ __('Date') }}
                  </label>
                  <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   wire:model="date" name="date" id="date" type="date">
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="place">
                    {{ __('Place') }}
                  </label>
                  <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   wire:model="place" name="place" id="place" type="text" placeholder="{{ __('Place') }}">
                </div>
                <div class="flex items-center justify-end">
                  <button wire:click="createTournament" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    {{ __('Create') }}
                  </button>
                </div>
              </form>
            </div>
        HTML;
    }
}
