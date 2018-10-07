<?php
namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class GameRecord extends Model {

    protected $table = 'games';

    protected $fillable = ['id', 'homeTeamId', 'awayTeamId', 'homeTeamGoals', 'awayTeamGoals'];

    public $timestamps = false;

}
