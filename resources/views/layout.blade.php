<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Kontrolní body</title>

    <!-- Styles -->
    <link href="{{asset('css/app.css')}}"
          rel="stylesheet"
          type="text/css">

    <script src="{{asset('js/app.js')}}"></script>

    @yield('scripts')
</head>
<body>
    <div class="container">
        <div class="page-header">
            <a href="/"><h1>Řízení hry</h1></a>
        </div>

        @yield('content')

    </div>
</body>
</html>
