<?php
namespace App\Team;

class TeamLeagueRecord {

    private $gamesPlayed = 0;
    private $gamesWon = 0;
    private $gamesDrawn = 0;
    private $gamesLost = 0;
    private $goalsScored = 0;
    private $goalsConceded = 0;
    private $points = 0;
    private $teamId;

    public function __construct(int $teamId) {
        $this->teamId = $teamId;
    }

    public function add_game(int $scored, int $conceded) {
        $this->gamesPlayed++;
        $this->goalsScored += $scored;
        $this->goalsConceded += $conceded;
        if ($scored > $conceded) {
            $this->points += 3;
            $this->gamesWon++;
        } elseif ($scored == $conceded) {
            $this->points++;
            $this->gamesDrawn++;
        } else {
            $this->gamesLost++;
        }
    }

    public function get_points(): int {
        return $this->points;
    }

    public function get_goals_scored(): int {
        return $this->goalsScored;
    }

    public function get_goals_conceded(): int {
        return $this->goalsConceded;
    }

    public function get_goal_difference(): int {
        return $this->goalsScored - $this->goalsConceded;
    }

    public function get_games_played(): int {
        return $this->gamesPlayed;
    }

    public function get_games_won(): int {
        return $this->gamesWon;
    }

    public function get_games_drawn(): int {
        return $this->gamesDrawn;
    }

    public function get_games_lost(): int {
        return $this->gamesLost;
    }

    public function get_team_id(): int {
        return $this->teamId;
    }
}
