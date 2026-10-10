<?php

use App\Models\Tournament;
use App\Models\User;

it('auto-generates a 6-character share code from the safe alphabet on creation', function () {
    $tournament = createTournament();

    expect($tournament->share_code)->toHaveLength(6);
    expect($tournament->share_code)->toMatch('/^[23456789ABCDEFGHJKMNPQRSTUVWXYZ]{6}$/');
});

it('never collides share codes across many tournaments', function () {
    $codes = collect(range(1, 25))->map(fn () => createTournament()->share_code);

    expect($codes->unique())->toHaveCount(25);
});

it('grants access to the owner and to attached collaborators, but no one else', function () {
    $tournament = createTournament();
    $collaborator = User::factory()->create();
    $stranger = User::factory()->create();

    $tournament->collaborators()->attach($collaborator->id);

    expect($tournament->hasAccess($tournament->user))->toBeTrue();
    expect($tournament->hasAccess($collaborator))->toBeTrue();
    expect($tournament->hasAccess($stranger))->toBeFalse();
});

it('attaches a user as a collaborator when joining with a valid code', function () {
    $tournament = createTournament();
    $joiner = User::factory()->create();

    $joined = Tournament::joinByCode($tournament->share_code, $joiner);

    expect($joined->id)->toBe($tournament->id);
    expect($joiner->sharedTournaments()->find($tournament->id))->not->toBeNull();
});

it('is case-insensitive and trims whitespace when joining by code', function () {
    $tournament = createTournament();
    $joiner = User::factory()->create();

    $joined = Tournament::joinByCode(' ' . strtolower($tournament->share_code) . ' ', $joiner);

    expect($joined->id)->toBe($tournament->id);
});

it('returns null for an unknown code', function () {
    $joiner = User::factory()->create();

    expect(Tournament::joinByCode('ZZZZZZ', $joiner))->toBeNull();
});

it('does not duplicate the collaborator row when joining twice or joining as the owner', function () {
    $tournament = createTournament();
    $joiner = User::factory()->create();

    Tournament::joinByCode($tournament->share_code, $joiner);
    Tournament::joinByCode($tournament->share_code, $joiner);
    Tournament::joinByCode($tournament->share_code, $tournament->user);

    expect($tournament->collaborators()->count())->toBe(1);
});
