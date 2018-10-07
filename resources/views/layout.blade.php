<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>League Table</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script
                src="https://code.jquery.com/jquery-3.3.1.min.js"
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Roboto', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .match-result {
                cursor: pointer;
            }

            .match-result input {
                width: 5rem;
            }

        </style>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var matchDay = parseInt("{{ $matchDayNumber }}", 10);
                var allMatchesMode = false;
                $('.action-nextweek').click(
                        function () {
                            matchDay += 1;
                            if (matchDay <= 6) {
                                $.post('/play', {"matchDay": matchDay}, function (data, status) {
                                    if (status == 'success') {
                                        $('.league-table').html(data.leagueTable);
                                        $('.match-results').html(data.matchResults);
                                        if (data.predictions) {
                                            $('.prediction-content').html(data.predictions);
                                            $('.prediction').show();
                                        }
                                    }
                                });
                            }
                        }
                );
                $('.action-reset').click(
                        function () {
                            matchDay = 1;
                            allMatchesMode = false;
                            $.post('/reset', function (data, status) {
                                if (status == 'success') {
                                    $('.league-table').html(data.leagueTable);
                                    $('.match-results').html(data.matchResults);
                                    $('.prediction').hide();
                                }
                            })
                        }
                );
                $('.action-playall').click(
                        function () {
                            allMatchesMode = true;
                            $.post('/play_all', function (data, status) {
                                if (status == 'success') {
                                    $('.league-table').html(data.leagueTable);
                                    $('.match-results').html(data.matchResults);
                                    $('.prediction').hide();
                                }
                            });
                        }
                );
                $('.match-results').on('click', '.match-result', function() {
                    $(this).find('span').hide();
                    $(this).find('div').show();
                });
                $('.match-results').on('click', '.match-result .action-update-game', function() {
                    var gameId = $(this).closest('td').data('game-id');
                    var data = {"result": $(this).closest('div').find('input').val()};
                    if (allMatchesMode) {
                        data.allMatches = true;
                    }
                    $.post('/game/' + gameId, data, function (data, status) {
                        if (status == 'success') {
                            $('.league-table').html(data.leagueTable);
                            $('.match-results').html(data.matchResults);
                            if (data.predictions) {
                                $('.prediction-content').html(data.predictions);
                                $('.prediction').show();
                            } else {
                                $('.prediction').hide();
                            }
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="main">
                    <div class="float-left card mr-5">
                        <div class="card-body">
                            <h4 class="text-center card-title">League Table</h4>

                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Teams</th>
                                        <th scope="col">PTS</th>
                                        <th scope="col">P</th>
                                        <th scope="col">W</th>
                                        <th scope="col">D</th>
                                        <th scope="col">L</th>
                                        <th scope="col">GD</th>
                                    </tr>
                                </thead>
                                <tbody class="league-table">
                                    @include('leaguetable')
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card float-left mr-5">
                        <div class="card-body">
                            <h4 class="text-center card-title">Match Results</h4>
                            <div class="match-results">
                                @include('matchresults')
                            </div>
                            <div class="match_controls">
                                <button type="button" class="btn btn-danger action-reset">Reset</button>
                                <button type="button" class="btn btn-secondary action-playall">Play All</button>
                                <button type="button" class="btn btn-primary action-nextweek">Next Week</button>
                            </div>
                        </div>
                    </div>
                    <div class="prediction card" @empty($predictions) style="display:none;" @endempty>
                        <div class="card-body prediction-content">
                            @include('prediction')
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>
