@extends('layouts.auth')
@section('content')
    <!-- forgot-password wrap -->
    <div class="row">
        <div class="col-12 col-md-6 col-xl-4 minvheight-100 d-flex flex-column px-0">
            <!-- standard header -->
            @include('partials.auth.auth-header')

            <div class="h-100 py-4 px-3">
                <div class="row h-100 align-items-center justify-content-center mt-md-4">
                    <div class="col-11 col-sm-8 col-md-11 col-xl-11 col-xxl-10 login-box">
                        <div class="mb-4">
                            <h1 class="h2 mt-auto">Forgot password?</h1>
                            <p class="pb-2 pb-md-3">Enter the email address you used when you joined and we'll send you instructions to reset your password</p>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('password.email') }}" method="POST" id="forgot-password-form" novalidate>
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email Address" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="email">Email Address</label>
                            </div>

                            <button type="submit" id="submit-button" class="btn btn-lg btn-theme w-100 mb-4" disabled>Reset password</button>

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
        // Initialize countdown timer
        let countdown = 0;
        let countdownInterval = null;

        // Check for throttle data from server (passed via Laravel)
        const throttleSeconds = {{ $errors->has('throttle') ? json_encode(max(0, round($errors->first('throttle')))) : 0 }};

        function startCountdown(seconds) {
            // Clear any existing interval
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdown = seconds;
            const submitButton = document.getElementById('submit-button');

            if (seconds > 0) {
                submitButton.textContent = `Resend in ${seconds}s`;
                submitButton.disabled = true;
                countdownInterval = setInterval(() => {
                    countdown--;
                    submitButton.textContent = `Resend in ${countdown}s`;
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        submitButton.textContent = 'Reset password';
                        submitButton.disabled = false;
                    }
                }, 1000);
            } else {
                submitButton.textContent = 'Reset password';
                submitButton.disabled = false;
            }
        }

        // Form validation
        const form = document.getElementById('forgot-password-form');
        const emailInput = document.getElementById('email');

        function validateForm() {
            let isValid = true;
            emailInput.classList.remove('is-invalid');
            const feedback = emailInput.nextElementSibling?.classList.contains('invalid-feedback') ? emailInput.nextElementSibling : null;
            if (feedback) feedback.textContent = '';

            if (!emailInput.value.trim()) {
                isValid = false;
                emailInput.classList.add('is-invalid');
                feedback.textContent = 'Email is required.';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                isValid = false;
                emailInput.classList.add('is-invalid');
                feedback.textContent = 'Please enter a valid email address.';
            }

            return isValid;
        }

        // Enable/disable button based on input and countdown
        function updateButtonState() {
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = countdown > 0 || !emailInput.value.trim();
        }

        // Event listeners
        emailInput.addEventListener('input', updateButtonState);

        form.addEventListener('submit', (event) => {
            if (!validateForm()) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        // Initialize countdown if throttle is active
        document.addEventListener('DOMContentLoaded', () => {
            startCountdown(throttleSeconds);
            updateButtonState();
        });

        // Cleanup interval on page unload
        window.addEventListener('beforeunload', () => {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        });
    </script>
@endpush
