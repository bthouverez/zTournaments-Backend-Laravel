<?php

use App\Livewire\TournamentList;
use App\Models\Tournament;
use App\Models\User;

function listComponent(User $user): TournamentList
{
    auth()->login($user);

    $component = new TournamentList();
    $component->mount();

    return $component;
}

it('separates owned tournaments from tournaments shared with the user', function () {
    $user = User::factory()->create();
    $owned = createTournament(['user_id' => $user->id]);
    $sharedWithMe = createTournament();
    $sharedWithMe->collaborators()->attach($user->id);
    createTournament(); // unrelated tournament, should show up in neither list

    $component = listComponent($user);

    expect($component->tournaments->pluck('id')->all())->toBe([$owned->id]);
    expect($component->sharedTournaments->pluck('id')->all())->toBe([$sharedWithMe->id]);
});

it('joins a tournament by its share code and redirects to its registration page', function () {
    $owner = User::factory()->create();
    $tournament = createTournament(['user_id' => $owner->id]);
    $joiner = User::factory()->create();

    $component = listComponent($joiner);
    $component->joinCode = $tournament->share_code;
    $response = $component->joinTournament();

    expect($joiner->sharedTournaments()->find($tournament->id))->not->toBeNull();
    expect($response->getTargetUrl())->toContain("/tournaments/{$tournament->id}/registration");
});

it('rejects an unknown share code without joining anything', function () {
    $user = User::factory()->create();
    $component = listComponent($user);
    $component->joinCode = 'ZZZZZZ';

    $component->joinTournament();

    expect($user->sharedTournaments()->count())->toBe(0);
    expect($component->getErrorBag()->has('joinCode'))->toBeTrue();
});

it('lets a collaborator delete the tournament, but blocks an unrelated user', function () {
    $owner = User::factory()->create();
    $tournament = createTournament(['user_id' => $owner->id]);
    $collaborator = User::factory()->create();
    $tournament->collaborators()->attach($collaborator->id);
    $stranger = User::factory()->create();

    $strangerComponent = listComponent($stranger);
    $strangerComponent->delete($tournament->id);
    expect(Tournament::find($tournament->id))->not->toBeNull();

    $collaboratorComponent = listComponent($collaborator);
    $collaboratorComponent->delete($tournament->id);
    expect(Tournament::find($tournament->id))->toBeNull();
});
