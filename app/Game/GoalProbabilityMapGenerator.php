<?php

namespace App\Game;

class GoalProbabilityMapGenerator {

    private $goalProbability;

    public function __construct(ProbabilityGetter $probability) {
        $this->goalProbability = $probability;
    }

    public function generate(float $mean): array {
        $map = [];
        for ($goals = 0; $goals < 10; $goals++) {
            $map[$goals] = $this->goalProbability->get($mean, $goals);
        };
        return $map;
    }
}
