<?php
namespace App\Prediction;

use App\League\League;

class LeagueEventsVariation {

    private $probability;
    private $league;

    public function __construct(float $probability, League $league) {
        $this->probability = $probability;
        $this->league = $league;
    }

    public function get_probability(): float {
        return $this->probability;
    }

    public function get_league(): League {
        return $this->league;
    }

}
