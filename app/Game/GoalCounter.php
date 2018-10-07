<?php
namespace App\Game;

class GoalCounter {

    private $goalProbability;

    public function __construct(ProbabilityGetter $probability) {
        $this->goalProbability = $probability;
    }

    private function get_rand(): float {
        return mt_rand() / mt_getrandmax();
    }

    public function count(float $mean): int {
        $rand = $this->get_rand();
        $goals = 0;
        $prob = 0;
        do {
            $prob += $this->goalProbability->get($mean, $goals);
            if ($prob > $rand) {
                return $goals;
            }
        } while ($goals++ < 100);
        return $goals;
    }
}
