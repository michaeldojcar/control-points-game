@extends('layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Archiv sehrávek kontrolních bodů</h2>

            <table>
                <tr>
                    <td>Zvolte pro zobrazení detailu sehrávky</td>

                </tr>
                @foreach($games as $game)
                    <tr>
                        <td><a href="{{route('controlPoints.game',['id'=>$game->game_id])}}">Sehrávka kontrolních bodů
                                č.{{$game->game_id}}</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


@endsection
