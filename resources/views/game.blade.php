@extends('layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Kontrolní body: sehrávka {{$game_id}}</h2>

            <p>{{$created_at}}</p>

            <table>
                <tr>
                    <td>1.</td>
                    <td>{{ $groups[0] }}</td>
                    <td>{{ $ranks[0] }} sekund</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>{{ $groups[1] }}</td>
                    <td>{{ $ranks[1] }} sekund</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>{{ $groups[2] }}</td>
                    <td>{{ $ranks[2] }} sekund</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>{{ $groups[3] }}</td>
                    <td>{{ $ranks[3] }} sekund</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>{{ $groups[4] }}</td>
                    <td>{{ $ranks[4] }} sekund</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>{{ $groups[5] }}</td>
                    <td>{{ $ranks[5] }} sekund</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>{{ $groups[6] }}</td>
                    <td>{{ $ranks[6] }} sekund</td>
                </tr>


            </table>
        </div>
    </div>


@endsection
