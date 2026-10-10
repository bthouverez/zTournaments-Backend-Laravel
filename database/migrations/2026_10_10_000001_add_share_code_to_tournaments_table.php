<?php

use App\Models\Tournament;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->string('share_code', 6)->nullable()->after('user_id');
        });

        Tournament::whereNull('share_code')->get()->each(function (Tournament $tournament) {
            $tournament->share_code = Tournament::generateUniqueShareCode();
            $tournament->save();
        });

        // The column stays nullable at the DB level (no doctrine/dbal installed to alter
        // it in place); Tournament::booted() always fills it in before a row is created.
        Schema::table('tournaments', function (Blueprint $table) {
            $table->unique('share_code');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropUnique(['share_code']);
            $table->dropColumn('share_code');
        });
    }
};
