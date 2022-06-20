@extends('layout')

@section('content')
    <h3>Přehled her</h3>

    <div class="row">
        <div class="col-md-6">
            <table class="table">
                @foreach($games as $game)
                    <tr>
                        <td>
                            <a href="{{route('games.show', $game)}}">
                                @if($game->start_at)
                                    {{$game->started_at->format('j.n.Y G:i')}}
                                @else
                                    {{$game->created_at->format('j.n.Y G:i')}}
                                @endif
                            </a>
                        </td>
                        <td>{{$game->status_string}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-6">
            @if(!$current_game)
                <a href="{{route('games.create')}}" class="btn btn-primary">Nová hra</a>
            @endif
        </div>
    </div>
@endsection
