@extends('layouts.auth')
@section('content')
    <!-- register wrap -->
    <div class="row">
        <div class="col-12 col-md-6 col-xl-4 minvheight-100 d-flex flex-column px-0">
            <!-- standard header -->
            @include('partials.auth.auth-header')

            <div class="h-100 py-4 px-3">
                <div class="row h-100 align-items-center justify-content-center mt-md-4">
                    <div class="col-11 col-sm-8 col-md-11 col-xl-11 col-xxl-10 login-box">
                        <div class="mb-4">
                            <h1 class="h2 mt-auto">Hi there,</h1>
                            <div class="nav fs-sm mb-4">
                                Already have an account?
                                <a class="text-decoration-underline p-0 ms-2" href="{{ route('login') }}">Sign In</a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('register.store') }}" method="POST" id="register-form" novalidate>
                            @csrf

                            @error('provider')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @enderror

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" autofocus placeholder="First Name" required>
                                        @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <label for="first_name">First Name</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" placeholder="Last Name" required>
                                        @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <label for="last_name">Last Name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Email Address" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="email">Email Address</label>
                            </div>

                            <div class="position-relative">
                                <div class="form-floating mb-4">
                                    <input id="checkstrength" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="checkstrength">Password</label>
                                </div>

                                <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 toggle-password" data-target="checkstrength">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <div class="d-flex flex-column gap-2 mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="policy" name="policy">
                                    <label for="policy" class="form-check-label">I have read and accept the <a href="/">Privacy Policy</a></label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-lg btn-theme w-100">Create an account</button>
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
