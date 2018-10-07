<?php
namespace App\Prediction;

class EventsAggregator {

    public function aggregate(array $eventsMap): array {
        $prediction = [];
        foreach ($eventsMap as $event) {
            /** @var LeagueEventsVariation $event */
            $winnerId = $event->get_league()->get_winner_team_id();
            if (!isset($prediction[$winnerId])) {
                $prediction[$winnerId] = $event->get_probability();
            } else {
                $prediction[$winnerId] += $event->get_probability();
            }
        }
        return $this->normalize($prediction);
    }

    private function normalize(array $prediction): array {
        $totalProbability = 0;
        foreach ($prediction as $teamId => $probability) {
            $totalProbability += $probability;
        }
        foreach ($prediction as $teamId => $probability) {
            $prediction[$teamId] = $probability / $totalProbability;
        }
        arsort($prediction);
        return $prediction;
    }

}
