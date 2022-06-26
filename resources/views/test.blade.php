@extends('layout')

@section('content')
    <h2>Testování herního systému</h2>
    <p>Tyto odkazy jsou určené pouze pro vývoj hry.</p>

    <a href="/api/control-points/1/capture?rfid=1">Bod A - zabrat týmem 1</a>
    <a href="/api/control-points/1/capture?rfid=2">Bod A - zabrat týmem 2</a>
@endsection
