<?php
namespace App\League;

use App\Game\Game;
use App\Game\GameRecord;
use App\Game\GameSimulator;
use App\Prediction\LeagueWinnerPredictor;
use App\Team\Team;
use Illuminate\Support\Facades\Config;

class LeagueSimulator {

    /** @var LeagueState */
    private $leagueState;
    /** @var CalendarGenerator */
    private $calendarGenerator;
    /** @var GameSimulator */
    private $gameSimulator;
    /** @var LeagueRecordAccumulator */
    private $leagueRecordAccumulator;
    /** @var LeagueWinnerPredictor */
    private $winnerPredictor;

    public function __construct(
        CalendarGenerator $calendarGenerator,
        GameSimulator $gameSimulator,
        LeagueRecordAccumulator $leagueRecordAccumulator,
        LeagueWinnerPredictor $winnerPredictor
    ) {
        $this->calendarGenerator = $calendarGenerator;
        $this->gameSimulator = $gameSimulator;
        $this->leagueRecordAccumulator = $leagueRecordAccumulator;
        $this->winnerPredictor = $winnerPredictor;
        $this->init();
    }

    public function show(): LeagueState {
        $matchDay = $this->retrieve_stored_games();
        if (!$matchDay) {
            $this->leagueState->set_current_match_day(++$matchDay);
            return $this->play_next();
        }
        $this->leagueState->set_current_match_day($matchDay);
        $this->add_predictions($matchDay);
        return $this->leagueState;
    }

    public function reset() {
        GameRecord::truncate();
        $this->leagueState->set_current_match_day(1);
        return $this->play_next();
    }

    public function play_all() {
        GameRecord::truncate();
        foreach($this->leagueState->get_calendar() as $matchDay => $matchDayGames) {
            foreach ($matchDayGames as $game) {
                /** @var Game $game */
                $this->run_game($game);
            }
        }
        return $this->leagueState;
    }

    public function retrieve_before(int $matchDay) {
        $this->leagueState->set_current_match_day($matchDay);
        $this->leagueState->get_league()->fill_played_games($matchDay, $this->leagueState->get_calendar());
        $this->fill_league_stats();
        return $this->play_next();
    }

    private function retrieve_stored_games(): int {
        $matchDay = $this->leagueState->get_league()->get_stored_matchday($this->leagueState->get_calendar());
        if ($matchDay > 0) {
            $this->leagueState->get_league()->fill_played_games($matchDay + 1, $this->leagueState->get_calendar());
            $this->fill_league_stats();
        }
        return $matchDay;
    }

    private function fill_league_stats() {
        foreach ($this->leagueState->get_calendar() as $matchDayGames) {
            foreach ($matchDayGames as $game) {
                /** @var Game $game */
                if ($game->was_played()) {
                    $this->leagueRecordAccumulator->accumulate(
                        $game,
                        $this->leagueState->get_league()->get_record_by_team_id($game->get_home_side()->get_id()),
                        $this->leagueState->get_league()->get_record_by_team_id($game->get_away_side()->get_id())
                    );
                }
            }
        }
    }

    private function init() {
        $teams = Team::all();
        $teamIds = [];
        foreach ($teams->all() as $team) {
            /** @var Team $team */
            $teamIds[] = $team->get_id();
        }
        $league = new League($teamIds);
        $calendar = $this->calendarGenerator->generate($teams->all());
        $this->leagueState = new LeagueState($league, $teams, $calendar);
    }

    private function add_predictions(int $matchDay) {
        if ($matchDay >= 4) {
            $prediction = $this->winnerPredictor->predict(
                $this->leagueState->get_league(),
                $this->leagueState->get_calendar(),
                Config::get('constants.homePower'),
                Config::get('constants.leagueGoals')
            );
            $this->leagueState->set_predictions($prediction);
        }
    }

    private function run_game(Game $game) {
        $gameResult = $this->gameSimulator->get_result(
            $game->get_home_side(),
            $game->get_away_side(),
            Config::get('constants.homePower'),
            Config::get('constants.leagueGoals')
        );
        $game->set_result($gameResult);
        $this->leagueRecordAccumulator->accumulate(
            $game,
            $this->leagueState->get_league()->get_record_by_team_id($game->get_home_side()->get_id()),
            $this->leagueState->get_league()->get_record_by_team_id($game->get_away_side()->get_id())
        );
        $game->store();
    }

    private function play_next(): LeagueState {
        foreach ($this->leagueState->get_calendar()[$this->leagueState->get_current_match_day()] as $game) {
            /** @var Game $game */
            $this->run_game($game);
        }
        $this->add_predictions($this->leagueState->get_current_match_day());
        return $this->leagueState;
    }

}
