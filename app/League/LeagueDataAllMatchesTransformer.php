<?php
namespace App\League;

class LeagueDataAllMatchesTransformer {

    private $daySuffixGenerator;

    public function __construct(MatchDaySuffixGenerator $daySuffixGenerator) {
        $this->daySuffixGenerator = $daySuffixGenerator;
    }

    public function transform(LeagueState $leagueState): array {
        $table = $leagueState->get_league()->get_sorted_table();
        $calendar = [];
        foreach ($leagueState->get_calendar() as $matchDay => $games) {
            $calendar[] = [
                'matchDayNumber' => $matchDay,
                'matchDaySuffix' => $this->daySuffixGenerator->generate($matchDay),
                'games'          => $games,
            ];
        }
        $vars = [
            'calendar'       => $calendar,
            'leagueTable'    => $table,
            'teams'          => $leagueState->get_teams(),
        ];
        return $vars;
    }

}
