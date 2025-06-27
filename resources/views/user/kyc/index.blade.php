@extends('layouts.app')
@section('content')
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <!-- card -->
                <div class="card rounded-4 border overflow-hidden mb-4">
                    <div class="card-body text-center">
                        <div class="card adminuiux-card text-center bg-gradient-5 mb-4">
                            <div class="card-body">
                                <span class="avatar avatar-100 bg-theme-1-subtle rounded-pill text-theme-1 my-3 my-lg-4">
                                    <i class="h1 bi {{ $icon }}"></i>
                                </span>
                            </div>
                        </div>

                        <h4>{{ $title }}</h4>

                        <p>{{ $static_message }}</p>

                        <p class="text-secondary small mb-4">
                            {{ $dynamic_message }}
                        </p>

                        <a href="{{ $action['route'] }}" class="btn {{ $color }} px-4">
                            {{ $action['text'] }} <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <p class="mb-2">
                        If you have any queries, feel free to connect with us at
                        <a href="mailto:{{ config('settings.site.email') }}">{{ config('settings.site.email') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
