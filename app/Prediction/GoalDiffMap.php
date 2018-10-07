<?php
namespace App\Prediction;

class GoalDiffMap {

    private $probability;
    private $diffProbabilities;

    public function __construct(float $probability, array $diffProbabilities = []) {
        $this->probability = $probability;
        $this->diffProbabilities = $diffProbabilities;
    }

    public function get_probability(): float {
        return $this->probability;
    }

    public function get_diff_probabilities(): array {
        return $this->diffProbabilities;
    }
}
