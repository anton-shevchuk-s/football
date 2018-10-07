@foreach ($leagueTable as $row)
    <tr>
        <th class="text-left" scope="row">{{
            $teams->filter(function($item) use ($row) { return $item->id == $row->get_team_id(); })->first()->get_name()
        }}</th>
        <td class="text-right">{{ $row->get_points() }}</td>
        <td class="text-right">{{ $row->get_games_played() }}</td>
        <td class="text-right">{{ $row->get_games_won() }}</td>
        <td class="text-right">{{ $row->get_games_drawn() }}</td>
        <td class="text-right">{{ $row->get_games_lost() }}</td>
        <td class="text-right">{{ $row->get_goal_difference() }}</td>
    </tr>
@endforeach
