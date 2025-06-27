<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KindredCause') }} | {{ $title }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com/"/>
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
        <link rel="shortcut icon" type="image/png" href="{{ asset('frontend/images/fav.png') }}"/>

        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ asset('frontend/css/slick.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-drawer.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('frontend/icons/style.css') }}"/>
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}"/>
        <link rel="stylesheet" href="{{ asset('frontend/css/animate-4.1.1.min.css') }}"/>

    </head>
    <body>

        @include('partials.header')

        @yield('content')

        @include('partials.footer')

        <script src="{{ asset('frontend/js/jquery-3.7.0.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery-migrate-3.4.1.js') }}"></script>
        <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
        <script src="{{ asset('frontend/js/waypoints.min.js') }}"></script>
        <script src="{{ asset('frontend/js/countUp.min.js') }}"></script>
        <script src="{{ asset('frontend/js/phosphor-icon.js') }}"></script>
        <script src="{{ asset('frontend/js/scrollreveal-4.0.0.min.js') }}"></script>
        <script src="{{ asset('frontend/js/bootstrap-drawer.min.js') }}"></script>
        <script src="{{ asset('frontend/js/drawer.min.js') }}"></script>
        <script src="{{ asset('frontend/js/main.min.js') }}"></script>
    </body>
</html>
