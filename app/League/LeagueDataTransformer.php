<?php
namespace App\League;

class LeagueDataTransformer {

    private $daySuffixGenerator;

    public function __construct(MatchDaySuffixGenerator $daySuffixGenerator) {
        $this->daySuffixGenerator = $daySuffixGenerator;
    }

    public function transform(LeagueState $leagueState): array {
        $table = $leagueState->get_league()->get_sorted_table();
        $vars = [
            'games'          => $leagueState->get_calendar()[$leagueState->get_current_match_day()],
            'leagueTable'    => $table,
            'matchDayNumber' => $leagueState->get_current_match_day(),
            'matchDaySuffix' => $this->daySuffixGenerator->generate($leagueState->get_current_match_day()),
            'teams'          => $leagueState->get_teams(),
        ];
        if ($leagueState->has_predictions()) {
            $vars['predictions'] = $leagueState->get_predictions();
        }
        return $vars;
    }

}
