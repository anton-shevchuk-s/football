<?php
namespace App\Prediction;

class GameOutcomeProbabilityMap {

    /** @var GoalDiffMap */
    private $homeWin;
    /** @var GoalDiffMap */
    private $draw;
    /** @var GoalDiffMap */
    private $awayWin;

    public function __construct(GoalDiffMap $homeWin, GoalDiffMap $draw, GoalDiffMap $awayWin) {
        $this->homeWin = $homeWin;
        $this->awayWin = $awayWin;
        $this->draw = $draw;
    }

    public function get_home_win(): GoalDiffMap {
        return $this->homeWin;
    }

    public function get_away_win(): GoalDiffMap {
        return $this->awayWin;
    }

    public function get_draw(): GoalDiffMap {
        return $this->draw;
    }
}
