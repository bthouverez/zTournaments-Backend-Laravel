<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bracket_id')->nullable()->constrained(
                table: 'pools', indexName: 'team_bracket_id'
            );
            $table->foreignId('playoff_id')->nullable()->constrained(
                table: 'pools', indexName: 'team_playoff_id'
            );
            $table->integer('score')->default(0);
            $table->foreignId('tournament_id')->constrained();
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
        Schema::dropIfExists('teams');
    }
}
