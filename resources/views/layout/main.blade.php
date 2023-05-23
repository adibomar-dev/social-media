<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SH - Adib Omar</title>
        <link rel="icon" href="{{ asset('images/social-media.png') }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="container-fluid bg-sm vh-100 p-0 overflow-hidden">
        @yield('content')

        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>
