<?php
namespace App\Prediction;

use App\Game\GoalProbabilityMapGenerator;
use App\Game\GoalMeanCalculator;
use App\Team\Team;


class GameOutcomeProbabilityGenerator {

    private $goalProbabilityMapGenerator;
    private $goalMeanCalculator;

    public function __construct(GoalProbabilityMapGenerator $goalProbabilityMapGenerator, GoalMeanCalculator $meanCalculator) {
        $this->goalProbabilityMapGenerator = $goalProbabilityMapGenerator;
        $this->goalMeanCalculator = $meanCalculator;
    }

    public function generate(Team $home, Team $away, float $leagueGoals, float $homeCoef): GameOutcomeProbabilityMap {
        $homeGoalsMap = $this->goalProbabilityMapGenerator->generate(
            $this->goalMeanCalculator->calculate($home->get_attack(), $away->get_defense(), $leagueGoals, $homeCoef)
        );
        $awayGoalsMap = $this->goalProbabilityMapGenerator->generate(
            $this->goalMeanCalculator->calculate($away->get_attack(), $home->get_defense(), $leagueGoals, 1 / $homeCoef)
        );
        $homeWinProbability = $awayWinProbability = $drawProbability = 0;
        $goalDiffProbability = [];
        foreach ($homeGoalsMap as $homeGoals => $homeGoalProbability) {
            foreach ($awayGoalsMap as $awayGoals => $awayGoalProbability) {
                $scoreProbability = $homeGoalProbability * $awayGoalProbability;
                if ($homeGoals === $awayGoals) {
                    $drawProbability += $scoreProbability;
                } elseif ($homeGoals > $awayGoals) {
                    $homeWinProbability += $scoreProbability;
                } else {
                    $awayWinProbability += $scoreProbability;
                }
                if (!isset($goalDiffProbability[$homeGoals - $awayGoals])) {
                    $goalDiffProbability[$homeGoals - $awayGoals] = $scoreProbability;
                } else {
                    $goalDiffProbability[$homeGoals - $awayGoals] += $scoreProbability;
                }
            }
        }
        return new GameOutcomeProbabilityMap(
            new GoalDiffMap($homeWinProbability),
            new GoalDiffMap($drawProbability),
            new GoalDiffMap($awayWinProbability)
        );
    }
}
