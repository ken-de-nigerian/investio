<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Required meta tags  -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>{{ config('app.name', 'KindredCause') }} | {{ $title }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
        <style>
            :root {
                --adminuiux-content-font: "Open Sans", sans-serif;
                --adminuiux-content-font-weight: 400;
                --adminuiux-title-font: "Lexend", sans-serif;
                --adminuiux-title-font-weight: 600;
            }
        </style>

        <script defer src="{{ asset('assets/js/app.js') }}"></script>
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/otp-code.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/create-goal.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive-table.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons/bootstrap-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">

        <!-- Styles -->
        @stack('styles')

        <style>
            .loader10 {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>

    <body
        class="main-bg main-bg-opac main-bg-blur adminuiux-sidebar-fill-white theme-grey roundedui scrolldown adminuiux-sidebar-standard"
        data-theme="theme-grey"
        data-sidebarfill="adminuiux-sidebar-fill-white"
        data-bs-spy="scroll"
        data-bs-target="#list-example"
        data-bs-smooth-scroll="true"
        tabindex="0"
    >
        <!-- Pageloader -->
        <div class="pageloader">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center text-center h-100">
                    <div class="col-12 mb-auto pt-4"></div>
                    <div class="col-auto">
                        <img src="{{ asset('assets/img/logo.svg') }}" alt="" class="height-60 mb-3" />
                        <p class="h6 mb-0">AdminUIUX</p>
                        <p class="h3 mb-4">Investment</p>
                        <div class="loader10 mb-2 mx-auto"></div>
                    </div>
                    <div class="col-12 mt-auto pb-4">
                        <p class="text-secondary">Please wait we are preparing awesome things to preview...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- standard header -->
        @include('partials.admin.header')

        <!-- page wrapper -->
        <div class="adminuiux-wrap">
            <!-- standard sidebar -->
            @include('partials.admin.sidebar')

            <main class="adminuiux-content has-sidebar" onclick="contentClick()">
                @yield('content')
            </main>
        </div>

        <!-- modals -->
        @include('partials.admin.modals')

        @if (Route::is('admin.profile') || Route::is('admin.users.edit'))
            <script src="{{ asset('assets/js/upload-profile-picture.js') }}"></script>
        @endif

        @if (Route::is('admin.users.show') || Route::is('admin.deposits'))
            <script src="{{ asset('assets/js/admin-manage-users.js') }}"></script>
        @endif

        @if (Route::is('admin.email.notifications'))
            <script src="{{ asset('assets/js/quill.js') }}"></script>
            <script src="{{ asset('assets/js/quill-editor.js') }}"></script>
        @endif

        <script src="{{ asset('assets/js/cleave.min.js') }}"></script>
        <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>

        <!-- Scripts -->
        @stack('scripts')

        <!-- Sessions Message -->
        @include('partials.message')

        <script>
            const iziToastSettings = {
                position: "topRight",
                timeout: 5000,
                resetOnHover: true,
                transitionIn: "flipInX",
                transitionOut: "flipOutX"
            };
        </script>
    </body>
</html>
