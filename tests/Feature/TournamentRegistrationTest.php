<?php

use App\Livewire\TournamentRegistration;
use App\Models\Player;
use App\Models\Team;

function registerComponent(\App\Models\Tournament $tournament): TournamentRegistration
{
    $component = new TournamentRegistration();
    $component->mount($tournament);

    return $component;
}

it('labels a singles team after the player name', function () {
    $tournament = createTournament(['team_size' => 1]);
    $component = registerComponent($tournament);

    $component->newPlayer1 = 'alice';
    $component->addTeam();

    $team = $tournament->teams()->first();
    expect($team->label)->toBe('Alice');
    expect($team->players)->toHaveCount(1);
});

it('labels a doubles team sequentially and keeps both players', function () {
    $tournament = createTournament(['team_size' => 2]);
    $component = registerComponent($tournament);

    $component->newPlayer1 = 'alice';
    $component->newPlayer2 = 'bob';
    $component->addTeam();

    $team = $tournament->teams()->first();
    expect($team->label)->toBe(__('Team') . ' 1');
    expect($team->players->pluck('name')->sort()->values()->all())->toBe(['Alice', 'Bob']);
});

it('removes a team along with its players', function () {
    $tournament = createTournament(['team_size' => 1]);
    $component = registerComponent($tournament);
    $component->newPlayer1 = 'alice';
    $component->addTeam();

    $team = $tournament->teams()->first();
    $playerId = $team->players->first()->id;

    $component->removeTeam($team->id);

    expect(Team::find($team->id))->toBeNull();
    expect(Player::find($playerId))->toBeNull();
});

it('reshuffles melee registrations into balanced teams without losing any player', function () {
    $tournament = createTournament(['team_size' => 2, 'melee' => true, 'has_brackets' => false]);
    $component = registerComponent($tournament);

    foreach (['Alice', 'Bob', 'Carol', 'Dave'] as $name) {
        $component->newPlayer1 = $name;
        $component->addTeam();
    }

    expect($tournament->teams()->count())->toBe(4);

    // Livewire re-hydrates $tournament from scratch on every real request; simulate that
    // here since this test drives the same component instance across multiple actions.
    $component->tournament = $tournament->fresh();
    $component->generate();

    $tournament->refresh();
    expect($tournament->teams)->toHaveCount(2);
    foreach ($tournament->teams as $team) {
        expect($team->players)->toHaveCount(2);
        expect($team->label)->not->toBe('');
    }

    $allPlayers = $tournament->teams->flatMap->players->pluck('name')->sort()->values()->all();
    expect($allPlayers)->toBe(['Alice', 'Bob', 'Carol', 'Dave']);

    // Melee without pools falls through to a playoff bracket, with both teams seeded
    expect($tournament->playoff)->not->toBeNull();
    expect($tournament->playoff->matches->contains(fn ($g) => $g->team_1_id && $g->team_2_id))->toBeTrue();
});
