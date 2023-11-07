<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $u = new User;
        $u->email = 'bthouverez@bthouverez.fr';
        $u->name = 'bthouverez';
        $u->password = Hash::make('321654');
        $u->save();

        $t = new Tournament;
        $t->label = 'Doublette promotion';
        $t->place = 'Trevoux';
        $t->date = '2023-11-05';
        $t->team_size = 2;
        $t->user_id = 1;
        $t->has_brackets = true;
        $t->save();

        $t = new Tournament;
        $t->label = 'Triplette vétéran';
        $t->place = 'Hauteville';
        $t->date = '2023-11-06';
        $t->team_size = 3;
        $t->user_id = 1;
        $t->has_brackets = true;
        $t->save();

        $t = new Tournament;
        $t->label = 'Tête-à-tête des nullos';
        $t->place = 'Simandre';
        $t->date = '2023-11-07';
        $t->team_size = 1;
        $t->user_id = 1;
        $t->has_brackets = true;
        $t->save();

        $t = new Tournament;
        $t->label = 'Précision de ouf';
        $t->place = 'L\'Orangerie';
        $t->date = '2023-07-31';
        $t->team_size = 0;
        $t->user_id = 1;
        $t->has_brackets = true;
        $t->save();

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
