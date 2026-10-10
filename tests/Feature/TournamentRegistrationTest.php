<?php

use App\Livewire\TournamentRegistration;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function registerComponent(\App\Models\Tournament $tournament): TournamentRegistration
{
    auth()->login($tournament->user);

    $component = new TournamentRegistration();
    $component->mount($tournament);

    return $component;
}

/**
 * Builds a stand-in for Livewire's uploaded file: loadPlayersFile() only ever
 * calls ->getRealPath() on it, so a tiny stub is enough to drive the import
 * without going through a real HTTP upload.
 */
function fakeUploadedXlsx(array $rows): object
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    foreach ($rows as $i => $row) {
        $line = $i + 2;
        foreach ($row as $col => $value) {
            $sheet->setCellValue(chr(66 + $col) . $line, $value);
        }
    }

    $path = sys_get_temp_dir() . '/players_import_test_' . uniqid() . '.xlsx';
    (new Xlsx($spreadsheet))->save($path);

    return new class($path)
    {
        public function __construct(private string $path)
        {
        }

        public function getRealPath(): string
        {
            return $this->path;
        }
    };
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

it('imports doubles teams from an uploaded spreadsheet', function () {
    $tournament = createTournament(['team_size' => 2]);
    $component = registerComponent($tournament);

    $component->playersListFile = fakeUploadedXlsx([
        ['alice', 'bob'],
        ['carol', 'dave'],
    ]);
    $component->loadPlayersFile();
    $tournament->refresh();

    expect($tournament->teams)->toHaveCount(2);
    $numbers = $tournament->teams->pluck('number')->sort()->values()->all();
    expect($numbers)->toBe([1, 2]);

    $allPlayers = $tournament->teams->flatMap->players->pluck('name')->sort()->values()->all();
    expect($allPlayers)->toBe(['Alice', 'Bob', 'Carol', 'Dave']);
});

it('numbers imported singles teams sequentially', function () {
    $tournament = createTournament(['team_size' => 1]);
    $component = registerComponent($tournament);

    $component->playersListFile = fakeUploadedXlsx([['alice'], ['bob'], ['carol']]);
    $component->loadPlayersFile();

    $numbers = $tournament->teams()->orderBy('id')->pluck('number')->all();
    expect($numbers)->toBe([1, 2, 3]);
});

it('stops importing at the first blank row', function () {
    $tournament = createTournament(['team_size' => 1]);
    $component = registerComponent($tournament);

    $component->playersListFile = fakeUploadedXlsx([['alice'], [''], ['carol']]);
    $component->loadPlayersFile();

    expect($tournament->teams()->count())->toBe(1);
    expect($tournament->teams()->first()->label)->toBe('Alice');
});

it('does not create a blank-named player when a doubles row is missing its second name', function () {
    $tournament = createTournament(['team_size' => 2]);
    $component = registerComponent($tournament);

    $component->playersListFile = fakeUploadedXlsx([['alice', '']]);
    $component->loadPlayersFile();

    $team = $tournament->teams()->first();
    expect($team->players)->toHaveCount(1);
    expect($team->players->first()->name)->toBe('Alice');
});

it('does nothing when no file was selected', function () {
    $tournament = createTournament(['team_size' => 1]);
    $component = registerComponent($tournament);

    $component->loadPlayersFile();

    expect($tournament->teams()->count())->toBe(0);
});

it('lets a collaborator added via the share code manage the tournament', function () {
    $tournament = createTournament();
    $collaborator = User::factory()->create();
    $tournament->collaborators()->attach($collaborator->id);
    auth()->login($collaborator);

    $component = new TournamentRegistration();
    $component->mount($tournament);

    expect($component->tournament->id)->toBe($tournament->id);
});

it('refuses access to a user who is neither the owner nor a collaborator', function () {
    $tournament = createTournament();
    $stranger = User::factory()->create();
    auth()->login($stranger);

    $component = new TournamentRegistration();

    try {
        $component->mount($tournament);
        $this->fail('Expected a 403 to be thrown for a user with no access to the tournament.');
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});
