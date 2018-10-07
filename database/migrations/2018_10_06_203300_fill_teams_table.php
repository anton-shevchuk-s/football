<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillTeamsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::table('teams')->truncate();
        DB::table('teams')->insert([
                [
                    'id'      => 1,
                    'name'    => 'Liverpool',
                    'attack'  => 60,
                    'defense' => 60,
                ],
                [
                    'id'      => 2,
                    'name'    => 'Arsenal',
                    'attack'  => 45,
                    'defense' => 45,
                ],
                [
                    'id'      => 3,
                    'name'    => 'Chelsea',
                    'attack'  => 75,
                    'defense' => 35,
                ],
                [
                    'id'      => 4,
                    'name'    => 'Man City',
                    'attack'  => 65,
                    'defense' => 65,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::table('teams')->truncate();
    }
}
