<h4 class="card-title">{{ $matchDayNumber }}<sup>{{ $matchDaySuffix }}</sup> week Predictions of Championship</h4>
<table class="table table-sm">
    <tbody>
        @isset ($predictions)
        @foreach ($predictions as $teamId => $probability)
            <tr>
                <td class="text-left">{{
                    $teams->filter(function($item) use ($teamId) { return $item->id == $teamId; })->first()->get_name()
                }}</td>
                <td class="text-right">{{ round($probability * 100) }}%</td>
            </tr>
        @endforeach
        @endisset
    </tbody>
</table>
