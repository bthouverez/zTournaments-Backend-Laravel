<?php

use App\Livewire\MatchCard;
use App\Models\Game;

function makeMatchCard(Game $match): MatchCard
{
    $component = new MatchCard();
    $component->mount($match, 'Test');

    return $component;
}

it('awards points and advances the winner and loser to their next matches', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);
    $tournament->generateBrackets(4);
    $tournament->refresh();

    $bracket = $tournament->brackets->first();
    $firstMatch = $bracket->matches->first();
    $teamA = $firstMatch->team_1;
    $teamB = $firstMatch->team_2;

    $component = makeMatchCard($firstMatch);
    $component->score_team_1 = 13;
    $component->score_team_2 = 7;
    $component->setScore($firstMatch);

    $firstMatch->refresh();
    $teamA->refresh();
    $teamB->refresh();

    expect((int) $firstMatch->team_1_score)->toBe(13);
    expect((int) $firstMatch->team_2_score)->toBe(7);
    expect((int) $teamA->score)->toBe(2);
    expect((int) $teamB->score)->toBe(-1);

    $winnersMatch = Game::find($firstMatch->winner_next_match_id);
    $losersMatch = Game::find($firstMatch->loser_next_match_id);
    expect($winnersMatch->team_1_id)->toBe($teamA->id);
    expect($losersMatch->team_1_id)->toBe($teamB->id);
});

it('ignores a 0-0 submission', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);
    $tournament->generateBrackets(4);
    $tournament->refresh();

    $firstMatch = $tournament->brackets->first()->matches->first();

    $component = makeMatchCard($firstMatch);
    $component->score_team_1 = 0;
    $component->score_team_2 = 0;
    $component->setScore($firstMatch);

    $firstMatch->refresh();
    expect((int) $firstMatch->team_1_score)->toBe(0);
    expect((int) $firstMatch->team_2_score)->toBe(0);
});

it('reverses a score correction and pulls the winner back out of the next match', function () {
    $tournament = createTournament(['team_size' => 2, 'has_brackets' => true]);
    createTeamsFor($tournament, 4);
    $tournament->generateBrackets(4);
    $tournament->refresh();

    $firstMatch = $tournament->brackets->first()->matches->first();
    $teamA = $firstMatch->team_1;
    $teamB = $firstMatch->team_2;

    $component = makeMatchCard($firstMatch);
    $component->score_team_1 = 13;
    $component->score_team_2 = 5;
    $component->setScore($firstMatch);

    $winnersMatch = Game::find($firstMatch->winner_next_match_id);
    expect($winnersMatch->team_1_id)->toBe($teamA->id);

    $component->selectedMatchId = $firstMatch->id;
    $component->resetScore();

    $firstMatch->refresh();
    $teamA->refresh();
    $teamB->refresh();
    $winnersMatch->refresh();

    expect((int) $firstMatch->team_1_score)->toBe(0);
    expect((int) $firstMatch->team_2_score)->toBe(0);
    expect((int) $teamA->score)->toBe(0);
    expect((int) $teamB->score)->toBe(0);
    expect($winnersMatch->team_1_id)->toBeNull();
    expect($winnersMatch->team_2_id)->toBeNull();
});
