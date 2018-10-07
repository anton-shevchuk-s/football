<?php
namespace App\Game;

use App\Team\Team;

class Game {

    private $id;
    private $home;
    private $away;
    private $result = null;

    public function __construct(int $id, Team $home, Team $away) {
        $this->id = $id;
        $this->home = $home;
        $this->away = $away;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_home_side(): Team {
        return $this->home;
    }

    public function get_away_side(): Team {
        return $this->away;
    }

    public function set_result(GameResult $result): Game {
        $this->result = $result;
        return $this;
    }

    public function was_played():bool {
        return $this->result instanceof GameResult;
    }

    public function get_result(): GameResult {
        return $this->result;
    }

    public function store() {
        $gameRecord = GameRecord::firstOrNew(['id' => $this->get_id()]);
        $gameRecord->homeTeamId = $this->get_home_side()->get_id();
        $gameRecord->awayTeamId = $this->get_away_side()->get_id();
        $gameRecord->homeTeamGoals = $this->was_played() ? $this->get_result()->get_home() : null;
        $gameRecord->awayTeamGoals = $this->was_played() ? $this->get_result()->get_away() : null;
        $gameRecord->save();
    }

}
