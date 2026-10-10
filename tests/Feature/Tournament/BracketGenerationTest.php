<?php

use App\Models\Game;
use App\Models\Pool;
use App\Models\Team;

it('splits teams evenly into brackets of the given size', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 8);

    $tournament->generateBrackets(4);
    $tournament->refresh();

    expect($tournament->brackets)->toHaveCount(2);
    expect($tournament->brackets->pluck('label')->sort()->values()->all())->toBe(['A', 'B']);

    foreach ($tournament->brackets as $bracket) {
        expect($bracket->bracket_teams)->toHaveCount(4);
        expect($bracket->matches)->toHaveCount(5);
    }

    expect(Team::where('tournament_id', $tournament->id)->where('label', 'like', 'Exempt%')->count())->toBe(0);
});

it('pads the last bracket with exempt teams when teams do not divide evenly', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 6);

    $tournament->generateBrackets(4);
    $tournament->refresh();

    expect($tournament->brackets)->toHaveCount(2);
    foreach ($tournament->brackets as $bracket) {
        expect($bracket->bracket_teams)->toHaveCount(4);
    }

    // 6 real teams + 2 filler "Exempt" teams = 8 = 2 brackets of 4
    $exemptCount = Team::where('tournament_id', $tournament->id)->where('label', 'like', 'Exempt%')->count();
    expect($exemptCount)->toBe(2);
});

it('wires each bracket into a winners/losers/decider mini-bracket', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);

    $tournament->generateBrackets(4);
    $tournament->refresh();

    $bracket = $tournament->brackets->first();
    [$game1, $game2, $winnersMatch, $losersMatch, $decider] = $bracket->matches->values()->all();

    expect((int) $game1->winner_next_match_id)->toBe($winnersMatch->id);
    expect((int) $game1->loser_next_match_id)->toBe($losersMatch->id);
    expect((int) $game2->winner_next_match_id)->toBe($winnersMatch->id);
    expect((int) $game2->loser_next_match_id)->toBe($losersMatch->id);

    expect((int) $winnersMatch->winner_next_match_id)->toBe(0);
    expect((int) $winnersMatch->loser_next_match_id)->toBe($decider->id);

    expect((int) $losersMatch->winner_next_match_id)->toBe($decider->id);
    expect((int) $losersMatch->loser_next_match_id)->toBe(0);

    expect((int) $decider->winner_next_match_id)->toBe(0);
    expect((int) $decider->loser_next_match_id)->toBe(0);

    // Round 1 covers every registered team exactly once
    $round1TeamIds = collect([$game1->team_1_id, $game1->team_2_id, $game2->team_1_id, $game2->team_2_id])
        ->sort()->values();
    $allTeamIds = $tournament->teams->pluck('id')->sort()->values();
    expect($round1TeamIds->all())->toBe($allTeamIds->all());
});

it('regenerating brackets clears the previous pools and games', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);

    $tournament->generateBrackets(4);
    $tournament->refresh();
    $firstPoolId = $tournament->brackets->first()->id;

    $tournament->generateBrackets(4);
    $tournament->refresh();

    expect(Pool::where('id', $firstPoolId)->exists())->toBeFalse();
    expect($tournament->brackets)->toHaveCount(1);
    expect(Game::where('tournament_id', $tournament->id)->count())->toBe(5);
});
