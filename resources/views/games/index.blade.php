@extends('layout')

@section('content')
    <h3>Přehled her</h3>

    <div class="row">
        <div class="col-md-6">
            @if($current_game)
                <div class="card mb-3">
                    <div class="card-body">
                        <a href="{{route('games.show', $current_game)}}">Aktuální sehrávka</a>
                    </div>
                </div>
                @endif

            <div class="card">
                <div class="card-header">
                    Seznam her
                </div>
                <div class="card-body">
                    <table class="table">
                        @forelse($games as $game)
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
                        @empty
                            <tr>
                                <td>Žádné sehrávky, vytvořte první.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @if(!$current_game)
                <a href="{{route('games.create')}}"
                   class="btn btn-primary">Nová hra</a>
            @endif
        </div>
    </div>
@endsection
