<?php
namespace App\Http\Controllers\League;

use App\Game\GameRecord;
use App\Http\Controllers\Controller;
use App\League\LeagueSimulator;
use Illuminate\Http\Request;
use App\League\LeagueDataJsonResponder;

class GameController extends Controller {

    private $leagueSimulator;
    private $jsonResponder;

    public function __construct(
        LeagueSimulator $leagueSimulator,
        LeagueDataJsonResponder $jsonResponder
    ) {
        $this->leagueSimulator = $leagueSimulator;
        $this->jsonResponder = $jsonResponder;
    }

    public function update(int $gameId, Request $request) {
        $result = $request->input('result');
        $allMatches = $request->input('allMatches', null);
        if ($this->is_valid($result)) {
            list($home, $away) = explode(':', $result);
            $gameRecord = GameRecord::find($gameId);
            if (!empty($gameRecord)) {
                $gameRecord->homeTeamGoals = $home;
                $gameRecord->awayTeamGoals = $away;
                $gameRecord->save();
            }
        }
        $leagueState = $this->leagueSimulator->show();
        if ($allMatches) {
            return $this->jsonResponder->respond_all_matches($leagueState);
        }
        return $this->jsonResponder->respond_one_day_matches($leagueState);
    }

    public function is_valid(string $result): bool {
        if(strlen($result) > 5 || !strpos($result, ':')) {
            return false;
        }
        list($home, $away) = explode(':', $result);
        if(!is_numeric($home) || !is_numeric($away)) {
            return false;
        }
        if ($home < 0 || $home >= 100) {
            return false;
        }
        if ($away < 0 || $away >= 100) {
            return false;
        }
        return true;
    }

}
