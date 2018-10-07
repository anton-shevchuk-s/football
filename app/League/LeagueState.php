<?php
namespace App\League;

use Illuminate\Database\Eloquent\Collection;

class LeagueState {

    private $league;
    private $teams;
    private $calendar;
    private $currentMatchDay;
    private $predictions;

    public function __construct(League $league, Collection $teams, array $calendar) {
        $this->league = $league;
        $this->calendar = $calendar;
        $this->teams = $teams;
    }

    public function set_current_match_day(int $currentMatchDay): LeagueState {
        $this->currentMatchDay = $currentMatchDay;
        return $this;
    }

    public function get_league(): League {
        return $this->league;
    }

    public function get_teams(): Collection {
        return $this->teams;
    }

    public function get_calendar(): array {
        return $this->calendar;
    }

    public function get_current_match_day(): int {
        return $this->currentMatchDay;
    }

    public function has_predictions(): bool {
        return !empty($this->predictions);
    }

    public function set_predictions(array $predictions): LeagueState {
        $this->predictions = $predictions;
        return $this;
    }

    public function get_predictions(): array {
        return $this->predictions;
    }

}
