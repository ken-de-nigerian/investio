@extends('layouts.app')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center">
            <!-- Welcome box -->
            <div class="col-12 col-md-10 col-lg-8 mb-4">
                <h3 class="fw-normal mb-0 text-secondary">Earn up to {{ config('settings.referral.commission') }}% commission</h3>
                <h1>By inviting friends to join our platform</h1>
            </div>

            <div class="col-12 py-2"></div>

            <!-- Copy referral link -->
            <div class="col-12 col-md-8 col-lg-6 col-xxl-5 mb-4">
                <p>Share your unique referral link with your network</p>
                <div class="input-group mb-3">
                    <input id="referralURL" type="text" class="form-control form-control-lg border-theme-1" placeholder="Your Referral Link" aria-describedby="button-addon2" value="{{ route('register', ['ref' => auth()->user()->profile->account_number ?? '']) }}" readonly>
                    <button class="btn btn-lg btn-outline-theme" type="button" id="button-addon2" onclick="copyToClipboard(document.getElementById('referralURL'))"><i class="bi bi-copy"></i></button>
                </div>
            </div>

            <div class="col-12 py-2"></div>

            <!-- Total registrations -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card">
                    <div class="card-body">
                        <h2>{{ $metrics['total_registrations'] ?? 0 }}</h2>
                        <p class="text-secondary small">Total Registrations</p>
                    </div>
                </div>
            </div>

            <!-- Referral earnings -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card position-relative overflow-hidden bg-theme-1 h-100">
                    <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-50">
                        <img src="{{ asset('assets/img/modern-ai-image/flamingo-4.jpg') }}" alt="Referral Earnings Background">
                    </div>
                    <div class="card-body z-index-1">
                        <div class="row gx-3 align-items-center h-100">
                            <div class="col-auto">
                                <span class="avatar avatar-60 text-bg-warning rounded">
                                    <i class="bi bi-cash-coin h4"></i>
                                </span>
                            </div>
                            <div class="col">
                                <h2>${{ number_format($metrics['referral_earnings'] ?? 0, 2) }}</h2>
                                <p>Referral Earnings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row align-items-center justify-content-center">
            <div class="col-12 mb-4">
                <h5>Discover how our referral program works</h5>
            </div>

            <!-- Step 1 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-link avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>1. Share Your Link</h6>
                <p class="text-secondary">Invite friends and family with your unique referral link</p>
            </div>

            <!-- Step 2 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-person avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>2. User Registration</h6>
                <p class="text-secondary">Your referrals sign up and join our investment platform</p>
            </div>

            <!-- Step 3 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-coin avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>3. Earn Commissions</h6>
                <p class="text-secondary">Earn {{ config('settings.referral.commissions') }}% when referrals deposit and invest</p>
            </div>

            <!-- Step 4 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-cash-stack avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>4. Withdraw Earnings</h6>
                <p class="text-secondary">Transfer your earnings to your bank account easily</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Copy Referral & Wallet-->
    <script>
        function copyToClipboard(inputField) {
            // Select the text inside the input field
            inputField.select();
            inputField.setSelectionRange(0, 99999); /* For mobile devices */

            const showError = (message) => {
                iziToast.error({ ...iziToastSettings, message });
            };

            const showSuccess = (message) => {
                iziToast.success({ ...iziToastSettings, message });
            };

            // Use Clipboard API to copy text
            navigator.clipboard.writeText(inputField.value)
                .then(() => {
                    // If copying was successful
                    showSuccess("Copied: " + inputField.value);
                })
                .catch(() => {
                    // If copying failed
                    showError("Failed to copy. Please try again.");
                });
        }
    </script>
@endpush
