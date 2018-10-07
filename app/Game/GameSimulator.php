<?php
namespace App\Game;

use App\Team\Team;

class GameSimulator {

    private $goalCounter;
    private $goalMeanCalculator;

    public function __construct(GoalCounter $goalCounter, GoalMeanCalculator $meanCalculator) {
        $this->goalCounter = $goalCounter;
        $this->goalMeanCalculator = $meanCalculator;
    }

    public function get_result(Team $home, Team $away, float $homeCoef, float $median): GameResult {
        $homeGoals = $this->goalCounter->count($this->goalMeanCalculator->calculate($home->get_attack(), $away->get_defense(), $median, $homeCoef));
        $awayGoals = $this->goalCounter->count($this->goalMeanCalculator->calculate($away->get_attack(), $home->get_defense(), $median, 1 / $homeCoef));
        return new GameResult($homeGoals, $awayGoals);
    }
}
