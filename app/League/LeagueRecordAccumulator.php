<?php
namespace App\League;

use App\Game\Game;
use App\Team\TeamLeagueRecord;

class LeagueRecordAccumulator {

    public function accumulate(Game $game, TeamLeagueRecord $home, TeamLeagueRecord $away) {
        $home->add_game($game->get_result()->get_home(), $game->get_result()->get_away());
        $away->add_game($game->get_result()->get_away(), $game->get_result()->get_home());
    }

}
