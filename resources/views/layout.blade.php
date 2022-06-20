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

    <meta name="theme-color" content="#000000"
          media="(prefers-color-scheme: dark)">

    <title>Kontrolní body - řízení hry</title>

    <!-- Styles -->
    <link href="{{asset('css/app.css')}}"
          rel="stylesheet"
          type="text/css">

</head>
<body>
    <div id="app">
        <div class="container mt-5">
            <div class="page-header">
                <a href="/"><h1>Kontrolní body</h1></a>
            </div>

            @yield('content')

        </div>
    </div>

    <script src="{{asset('js/app.js')}}"></script>

    @yield('scripts')
</body>
</html>
