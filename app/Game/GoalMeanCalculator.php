<?php

namespace App\Game;

class GoalMeanCalculator {

    public function calculate(float $attack, float $defense, float $median, float $homeCoef): float {
        return $homeCoef * ($attack / $defense) * $median;
    }
}
