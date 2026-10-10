<?php

use App\Models\Game;
use App\Models\Player;
use App\Models\Pool;
use App\Models\Team;

it('resetMatches only removes games and pools for its own tournament', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);
    $tournament->generateBrackets(4);

    $otherTournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($otherTournament, 4);
    $otherTournament->generateBrackets(4);

    $tournament->resetMatches();

    expect(Game::where('tournament_id', $tournament->id)->count())->toBe(0);
    expect(Pool::where('tournament_id', $tournament->id)->count())->toBe(0);
    expect(Game::where('tournament_id', $otherTournament->id)->count())->toBe(5);
    expect(Pool::where('tournament_id', $otherTournament->id)->count())->toBe(1);
});

it('resetTeams removes players, teams and matches for its own tournament only', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    $teams = createTeamsFor($tournament, 4);
    foreach ($teams as $team) {
        $player = new Player();
        $player->name = 'Player ' . $team->id;
        $player->team_id = $team->id;
        $player->save();
    }
    $tournament->generateBrackets(4);

    $otherTournament = createTournament(['team_size' => 2]);
    $otherTeam = createTeamsFor($otherTournament, 1)->first();
    $otherPlayer = new Player();
    $otherPlayer->name = 'Untouched';
    $otherPlayer->team_id = $otherTeam->id;
    $otherPlayer->save();

    $tournament->resetTeams();

    expect(Team::where('tournament_id', $tournament->id)->count())->toBe(0);
    expect(Player::whereIn('team_id', $teams->pluck('id'))->count())->toBe(0);
    expect(Game::where('tournament_id', $tournament->id)->count())->toBe(0);

    expect(Team::where('tournament_id', $otherTournament->id)->count())->toBe(1);
    expect(Player::where('id', $otherPlayer->id)->exists())->toBeTrue();
});
