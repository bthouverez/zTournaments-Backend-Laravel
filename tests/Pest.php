<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createTournament(array $attributes = []): \App\Models\Tournament
{
    $user = \App\Models\User::factory()->create();

    $tournament = new \App\Models\Tournament();
    $tournament->label = $attributes['label'] ?? 'Tournoi de test';
    $tournament->date = $attributes['date'] ?? now()->toDateString();
    $tournament->place = $attributes['place'] ?? null;
    $tournament->team_size = $attributes['team_size'] ?? 2;
    $tournament->has_brackets = $attributes['has_brackets'] ?? false;
    $tournament->melee = $attributes['melee'] ?? false;
    $tournament->user_id = $attributes['user_id'] ?? $user->id;
    $tournament->save();

    return $tournament;
}

function createTeamsFor(\App\Models\Tournament $tournament, int $count): \Illuminate\Support\Collection
{
    return collect(range(1, $count))->map(function (int $ii) use ($tournament) {
        $team = new \App\Models\Team();
        $team->label = "Team {$ii}";
        $team->number = $ii;
        $team->tournament_id = $tournament->id;
        $team->save();

        return $team;
    });
}
