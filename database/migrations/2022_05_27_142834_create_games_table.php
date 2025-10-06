<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id');
            $table->foreignId('pool_id');
            $table->foreignId('team_1_id')->nullable()->constrained(
                table: 'teams', indexName: 'game_team_1'
            );
            $table->foreignId('team_2_id')->nullable()->constrained(
                table: 'teams', indexName: 'game_team_2'
            );
            $table->integer('field')->nullable()->default(null);
            $table->integer('team_1_score')->default(0);
            $table->integer('team_2_score')->default(0);

            # prochain match de l'équipe gagnante
            # 0 = qualifié
            $table->unsignedBigInteger('winner_next_match_id')->nullable();
//            $table->foreign('winner_next_match_id')->references('id')->on('games');
//            $table->foreignId('winner_next_match_id')->nullable()->constrained(
//                table: 'games', indexName: 'game_winner'
//            );

            # prochain match de l'équipe perdante
            # 0 = disqualifié
            $table->unsignedBigInteger('loser_next_match_id')->nullable();
//            $table->foreign('loser_next_match_id')->references('id')->on('games');
//            $table->foreignId('loser_next_match_id')->nullable()->constrained(
//                table: 'games', indexName: 'game_loser'
//            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
