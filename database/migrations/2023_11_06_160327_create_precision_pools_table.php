<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('precision_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained();
            $table->integer('current_activity')->default(1);
            $table->integer('current_player_index')->default(0);
            $table->integer('current_distance')->default(6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precision_pools');
    }
};
