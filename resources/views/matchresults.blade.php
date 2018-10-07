<p>
    {{ $matchDayNumber }}<sup>{{ $matchDaySuffix }}</sup> week Match Results
</p>
<table class="table table-sm">
    @foreach ($games as $game)
        <tr>
            <td>{{ $game->get_home_side()->get_name() }}</td>
            <td class="text-center match-result" data-game-id="{{ $game->get_id() }}">
                <span>{{ $game->get_result() }}</span>
                <div style="display: none;">
                    <input class="text" value="{{ $game->get_result() }}" maxlength="5"/>
                    <button type="button" class="btn btn-danger action-update-game">Update</button>
                </div>
            </td>
            <td class="text-right">{{ $game->get_away_side()->get_name() }}</td>
        </tr>
    @endforeach
</table>