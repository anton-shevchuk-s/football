<?php
namespace App\League;

use App\Game\Game;
use App\Game\GameRecord;
use App\Game\GameResult;
use App\Team\TeamLeagueRecord;

class League {

    /** @var array|TeamLeagueRecord[] */
    private $leagueTeams = [];

    public function __construct(array $teamIds) {
        foreach ($teamIds as $teamId) {
            $this->set_team_record(new TeamLeagueRecord($teamId));
        }
    }

    public function set_team_record(TeamLeagueRecord $leagueRecord): League {
        $this->leagueTeams[$leagueRecord->get_team_id()] = $leagueRecord;
        return $this;
    }

    public function get_team_records(): array {
        return $this->leagueTeams;
    }

    public function get_sorted_table(): array {
        $leagueTable = $this->leagueTeams;
        usort($leagueTable, array($this, 'compare'));
        return $leagueTable;
    }

    public function compare(TeamLeagueRecord $a, TeamLeagueRecord $b): int {
        if ($a->get_points() > $b->get_points()) {
            return -1;
        } elseif ($b->get_points() > $a->get_points()) {
            return 1;
        }
        if ($a->get_goal_difference() > $b->get_goal_difference()) {
            return -1;
        } elseif ($b->get_goal_difference() > $a->get_goal_difference()) {
            return 1;
        }
        if ($a->get_goals_scored() > $b->get_goals_scored()) {
            return -1;
        } elseif ($b->get_goals_scored() > $a->get_goals_scored()) {
            return 1;
        }
        return 0;
    }

    public function get_winner_team_id(): int {
        $leagueTable = $this->get_sorted_table();
        return $leagueTable[0]->get_team_id();
    }

    public function get_record_by_team_id(int $teamId): ?TeamLeagueRecord {
        foreach ($this->leagueTeams as $teamLeagueRecord) {
            if ($teamId === $teamLeagueRecord->get_team_id()) {
                return $teamLeagueRecord;
            }
        }
        return null;
    }

    public function fill_played_games(int $beforeMatchDay, array $matchDays) {
        $storedGameRecords = GameRecord::all()->keyBy('id');
        foreach ($matchDays as $matchDay => $matchDayGames) {
            foreach ($matchDayGames as $game) {
                /** @var Game $game */
                if ($matchDay < $beforeMatchDay && !empty($storedGameRecords[$game->get_id()])) {
                    $game->set_result(
                        new GameResult(
                            $storedGameRecords[$game->get_id()]->homeTeamGoals,
                            $storedGameRecords[$game->get_id()]->awayTeamGoals
                        )
                    );
                }
            }
        }
    }

    public function get_stored_matchday(array $matchDays): int {
        $storedGameRecords = GameRecord::all()->keyBy('id');
        if (empty($matchDays)) {
            return 0;
        }
        foreach ($matchDays as $matchDay => $matchDayGames) {
            foreach($matchDayGames as $game) {
                if (empty($storedGameRecords[$game->get_id()])) {
                    return $matchDay - 1;
                }
            }
        }
        return $matchDay;
    }

}
