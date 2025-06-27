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
                            <h1 class="h2 mt-auto">Reset Password</h1>
                            <div class="nav fs-sm mb-4">
                                Set new password for email:
                                <a class="nav-link text-decoration-underline p-0 ms-2">{{ $email }}</a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">

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

                            <div class="position-relative">
                                <div class="form-floating mb-4">
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="confirm-password" placeholder="Confirm Password">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="password_confirmation">Confirm Password</label>
                                </div>

                                <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 toggle-password" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <button type="submit" class="btn btn-lg btn-theme w-100 mb-4">Reset Password</button>

                            <div class="text-center mb-3">
                                Already have password? <a href="{{ route('login') }}" class=" ">Login</a> here.
                            </div>
                        </form>
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
