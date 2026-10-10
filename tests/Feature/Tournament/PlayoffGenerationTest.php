<?php

use App\Models\Game;

function nextPowerOfTwo(int $n): int
{
    $p = 1;
    while ($p < $n) {
        $p *= 2;
    }

    return $p;
}

it('builds a single-elimination tree for a power-of-two number of teams', function (int $teamCount) {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => false]);
    createTeamsFor($tournament, $teamCount);

    $tournament->generatePlayoff();
    $tournament->refresh();

    $playoff = $tournament->playoff;
    expect($playoff)->not->toBeNull();
    expect($playoff->label)->toBe('principal');

    $games = $playoff->matches;
    expect($games)->toHaveCount(nextPowerOfTwo($teamCount) - 1);

    // Exactly one terminal match: the final
    $finals = $games->filter(fn (Game $g) => (int) $g->winner_next_match_id === 0 && (int) $g->loser_next_match_id === 0);
    expect($finals)->toHaveCount(1);

    // No consolation / 3rd place matches exist in this format
    expect($games->every(fn (Game $g) => (int) $g->loser_next_match_id === 0))->toBeTrue();

    // Every registered team is accounted for exactly once, none lost or duplicated
    $teamIdsInGames = $games->flatMap(fn (Game $g) => [$g->team_1_id, $g->team_2_id])
        ->filter()
        ->unique()
        ->sort()
        ->values();
    expect($teamIdsInGames->all())->toBe($tournament->teams->pluck('id')->sort()->values()->all());
})->with([4, 8]);

it('gives byes to the teams left over when the field is not a power of two', function (int $teamCount) {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => false]);
    createTeamsFor($tournament, $teamCount);

    $tournament->generatePlayoff();
    $tournament->refresh();

    $games = $tournament->playoff->matches;
    $target = nextPowerOfTwo($teamCount);

    expect($games)->toHaveCount($target - 1);

    $byeMatches = $games->filter(
        fn (Game $g) => $g->team_1_id !== null && $g->team_1_id === $g->team_2_id
    );
    expect($byeMatches)->toHaveCount($target - $teamCount);

    $teamIdsInGames = $games->flatMap(fn (Game $g) => [$g->team_1_id, $g->team_2_id])
        ->filter()
        ->unique()
        ->sort()
        ->values();
    expect($teamIdsInGames->all())->toBe($tournament->teams->pluck('id')->sort()->values()->all());
})->with([3, 5, 6, 7]);
