@extends('layout')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <h3>Probíhající hra</h3>

            Stav hry: {{$game->getStatusString()}}
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Ovládání hry
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection
