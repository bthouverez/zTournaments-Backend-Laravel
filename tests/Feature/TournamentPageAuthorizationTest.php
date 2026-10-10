<?php

use App\Livewire\TournamentBracket;
use App\Livewire\TournamentPlayoff;
use App\Livewire\TournamentPrecision;
use App\Models\User;

/**
 * TournamentRegistrationTest covers the same abort_unless() guard in detail
 * (collaborator access, error code). This just confirms the other tournament
 * management pages got the same guard, not just the registration screen.
 */
it('blocks a user with no access from the bracket, playoff and precision screens', function (string $componentClass) {
    // The abort_unless() guard is the first line of each mount(), before anything
    // else is touched, so an otherwise-empty tournament is enough to test it.
    $tournament = createTournament();
    $stranger = User::factory()->create();
    auth()->login($stranger);

    $component = new $componentClass();

    try {
        $component->mount($tournament);
        test()->fail("Expected a 403 from {$componentClass}::mount() for a user with no access.");
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
})->with([
    TournamentBracket::class,
    TournamentPlayoff::class,
    TournamentPrecision::class,
]);
