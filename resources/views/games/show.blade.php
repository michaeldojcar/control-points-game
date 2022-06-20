@extends('layout')

@section('content')
    <game :game_id="{{$game->id}}"/>
@endsection
