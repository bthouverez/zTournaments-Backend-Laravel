<?php

use App\Models\PrecisionPool;

it('creates a single precision pool for a tournament', function () {
    $tournament = createTournament(['team_size' => 0]);

    $tournament->generatePrecision();
    $tournament->refresh();

    expect($tournament->precision)->toHaveCount(1);
    $pool = $tournament->precision->first();
    expect($pool->label)->toBe('precision');
    expect((int) $pool->type)->toBe(3);
    expect(PrecisionPool::where('pool_id', $pool->id)->count())->toBe(1);
});

it('does not duplicate the precision pool if generated twice', function () {
    $tournament = createTournament(['team_size' => 0]);

    $tournament->generatePrecision();
    $tournament->refresh();
    $firstPoolId = $tournament->precision->first()->id;

    $tournament->generatePrecision();
    $tournament->refresh();

    expect($tournament->precision)->toHaveCount(1);
    expect($tournament->precision->first()->id)->toBe($firstPoolId);
});

it('redirects to the precision screen', function () {
    $tournament = createTournament(['team_size' => 0]);

    $response = $tournament->generatePrecision();

    expect($response->getTargetUrl())->toContain("/tournaments/{$tournament->id}/precision");
});
