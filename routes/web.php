<?php

use App\Livewire\TournamentBracket;
use App\Livewire\TournamentList;
use App\Livewire\TournamentPlayoff;
use App\Livewire\TournamentPrecision;
use App\Livewire\TournamentRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('test', function() {
//    dd(\Illuminate\Support\Facades\Storage::get('tmpTab.xlsx'));
    dd(storage_path());

});

if (env('APP_ENV') == 'local') {
    Auth::login(User::find(1));
}

Route::get('/', function () {
    return view('tournoi-landing');
//    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/tournaments', TournamentList::class)->name('tournaments');
    Route::get('/tournaments/{tournament}/registration', TournamentRegistration::class);
    Route::get('/tournaments/{tournament}/bracket', TournamentBracket::class);
    Route::get('/tournaments/{tournament}/playoff', TournamentPlayoff::class);
    Route::get('/tournaments/{tournament}/precision', TournamentPrecision::class);
});
