<?php
namespace App\League;

use App\Game\Game;
use App\Team\Team;

class CalendarGenerator {

    /**
     * @param array $teams|Team[]
     *
     * @return array
     * @throws \Exception
     */
    public function generate(array $teams): array {
        if (!is_array($teams) || count($teams) !== 4) {
            throw new \Exception('Only 4 teams in tournament are supported');
        }
        foreach ($teams as $team) {
            if (!$team instanceof Team) {
                throw new \Exception('Wrong instance passed to Calendar, expecting Team');
            }
        }
        return [
            1 => [new Game(1, $teams[0], $teams[1]), new Game(2, $teams[2], $teams[3])],
            2 => [new Game(3, $teams[0], $teams[2]), new Game(4, $teams[1], $teams[3])],
            3 => [new Game(5, $teams[0], $teams[3]), new Game(6, $teams[2], $teams[1])],
            4 => [new Game(7, $teams[1], $teams[0]), new Game(8, $teams[3], $teams[2])],
            5 => [new Game(9, $teams[2], $teams[0]), new Game(10, $teams[3], $teams[1])],
            6 => [new Game(11, $teams[3], $teams[0]), new Game(12, $teams[1], $teams[2])],
        ];
    }

}
