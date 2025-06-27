@extends('layouts.auth')
@section('content')
    <!-- login wrap -->
    <div class="row">
        <div class="col-12 col-md-6 col-xl-4 minvheight-100 d-flex flex-column px-0">
            <!-- standard header -->
            @include('partials.auth.auth-header')

            <div class="h-100 py-4 px-3">
                <div class="row h-100 align-items-center justify-content-center mt-md-4">
                    <div class="col-11 col-sm-8 col-md-11 col-xl-11 col-xxl-10 login-box">
                        <div class="mb-4">
                            <h1 class="h2 mt-auto">Welcome,</h1>
                            <div class="nav fs-sm mb-4">
                                Don't have an account?
                                <a class="text-decoration-underline p-0 ms-2" href="{{ route('register') }}">Sign Up</a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf

                            @error('provider')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $message }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @enderror

                            <div class="form-floating mb-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email Address">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="email">Email Address</label>
                            </div>

                            <div class="position-relative">
                                <div class="form-floating mb-4">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="password">Password</label>
                                </div>

                                <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 toggle-password" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <div class="row align-items-center mb-4">
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember" class="form-check-label">Remember for 30 days</label>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('password.request') }}" class="">Forgot Password?</a>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-lg btn-theme w-100">Login</button>
                        </form>

                        @include('partials.auth.social-logins', ['social_login' => $social_login])
                    </div>
                </div>
            </div>
        </div>

        @include('partials.auth.cover-image')
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (passwordInput && icon) {
                    const isVisible = passwordInput.type === 'text';
                    passwordInput.type = isVisible ? 'password' : 'text';
                    icon.className = isVisible ? 'bi bi-eye' : 'bi bi-eye-slash';
                }
            });
        });
    </script>
@endpush
