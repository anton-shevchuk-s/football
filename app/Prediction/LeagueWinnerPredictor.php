<?php
namespace App\Prediction;

use App\Game\Game;
use App\Game\GameResult;
use App\League\LeagueRecordAccumulator;
use App\League\League;

class LeagueWinnerPredictor {

    private $gameOutcomeProbabilityGenerator;
    private $leagueRecordAccumulator;
    private $eventsAggregator;

    public function __construct(
        GameOutcomeProbabilityGenerator $gameOutcomeProbabilityGenerator,
        LeagueRecordAccumulator $leagueRecordAccumulator,
        EventsAggregator $eventsAggregator
    ) {
        $this->gameOutcomeProbabilityGenerator = $gameOutcomeProbabilityGenerator;
        $this->leagueRecordAccumulator = $leagueRecordAccumulator;
        $this->eventsAggregator = $eventsAggregator;
    }

    public function predict(League $league, array $calendar, float $homeCoef, float $leagueGoals): array {
        $gamesLeft = [];
        foreach ($calendar as $matchday => $matchDayGames) {
            foreach ($matchDayGames as $game) {
                /** @var Game $game */
                if (!$game->was_played()) {
                    $gamesLeft[] = $game;
                }
            }
        }
        if (count($gamesLeft) > 4) {
            throw new \Exception('Too many games left to determine league winner');
        }
        if (!count($gamesLeft)) {
            return [$league->get_winner_team_id() => 1];
        }
        $eventsArray = [
            new LeagueEventsVariation(1, clone $league),
        ];
        foreach ($gamesLeft as $game) {
            $gameOutcomeMap = $this->gameOutcomeProbabilityGenerator->generate($game->get_home_side(), $game->get_away_side(), $leagueGoals, $homeCoef);
            $eventsArray = $this->add_game_map($game, $gameOutcomeMap, $eventsArray);
        }
        return $this->eventsAggregator->aggregate($eventsArray);
    }

    private function add_game_map(Game $game, GameOutcomeProbabilityMap $gameOutcomeMap, array $previousEvents): array {
        $eventsMap = [];
        foreach ($previousEvents as $previousEvent) {
            /** @var LeagueEventsVariation $previousEvent */
            $eventsMap[] = new LeagueEventsVariation(
                $previousEvent->get_probability() * $gameOutcomeMap->get_home_win()->get_probability(),
                $this->add_league_record($previousEvent->get_league(), (clone $game)->set_result(new GameResult(1, 0)))
            );
            $eventsMap[] = new LeagueEventsVariation(
                $previousEvent->get_probability() * $gameOutcomeMap->get_away_win()->get_probability(),
                $this->add_league_record($previousEvent->get_league(), (clone $game)->set_result(new GameResult(0, 1)))
            );
            $eventsMap[] = new LeagueEventsVariation(
                $previousEvent->get_probability() * $gameOutcomeMap->get_draw()->get_probability(),
                $this->add_league_record($previousEvent->get_league(), (clone $game)->set_result(new GameResult(0, 0)))
            );
        }
        return $eventsMap;
    }

    private function add_league_record(League $league, Game $game): League {
        $homeTeamRecord = clone $league->get_record_by_team_id($game->get_home_side()->get_id());
        $awayTeamRecord = clone $league->get_record_by_team_id($game->get_away_side()->get_id());
        $this->leagueRecordAccumulator->accumulate($game, $homeTeamRecord, $awayTeamRecord);
        return (clone $league)->set_team_record($homeTeamRecord)->set_team_record($awayTeamRecord);
    }

}
