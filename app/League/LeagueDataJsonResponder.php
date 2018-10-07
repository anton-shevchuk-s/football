<?php
namespace App\League;

class LeagueDataJsonResponder {

    private $leagueDataTransformer;
    private $leagueDataAllMatchesTransformer;

    public function __construct(
        LeagueDataTransformer $leagueDataTransformer,
        LeagueDataAllMatchesTransformer $leagueDataAllMatchesTransformer
    ) {
        $this->leagueDataTransformer = $leagueDataTransformer;
        $this->leagueDataAllMatchesTransformer = $leagueDataAllMatchesTransformer;
    }

    public function respond_all_matches(LeagueState $leagueState) {
        $variables = $this->leagueDataAllMatchesTransformer->transform($leagueState);
        $contents = ['leagueTable' => view('leaguetable', $variables)->render(), 'matchResults' => view('allmatchresults', $variables)->render()];
        return response()->json($contents);
    }

    public function respond_one_day_matches(LeagueState $leagueState) {
        $variables = $this->leagueDataTransformer->transform($leagueState);
        $contents = ['leagueTable' => view('leaguetable', $variables)->render(), 'matchResults' => view('matchresults', $variables)->render()];
        if ($leagueState->has_predictions()) {
            $contents['predictions'] = view('prediction', $variables)->render();
        }
        return response()->json($contents);
    }

}
