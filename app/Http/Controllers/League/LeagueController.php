<?php
namespace App\Http\Controllers\League;

use App\League\LeagueDataTransformer;
use App\League\LeagueSimulator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\League\LeagueDataJsonResponder;

class LeagueController extends Controller {

    private $leagueSimulator;
    private $leagueDataTransformer;
    private $jsonResponder;

    public function __construct(
        LeagueSimulator $leagueSimulator,
        LeagueDataTransformer $leagueDataTransformer,
        LeagueDataJsonResponder $jsonResponder
    ) {
        $this->leagueSimulator = $leagueSimulator;
        $this->leagueDataTransformer = $leagueDataTransformer;
        $this->jsonResponder = $jsonResponder;
    }

    public function show() {
        return view('layout', $this->leagueDataTransformer->transform($this->leagueSimulator->show()));
    }

    public function play_next(Request $request) {
        $matchDay = $request->input('matchDay');
        return $this->jsonResponder->respond_one_day_matches($this->leagueSimulator->retrieve_before($matchDay));
    }

    public function reset() {
        return $this->jsonResponder->respond_one_day_matches($this->leagueSimulator->reset());
    }

    public function play_all() {
        return $this->jsonResponder->respond_all_matches($this->leagueSimulator->play_all());
    }

}
