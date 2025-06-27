@extends('layouts.app')
@section('content')
    <!-- Content -->
    <div class="container my-5" id="main-content">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="invoice-box border rounded-4 p-4 p-md-5">
                    <!-- Header -->
                    <div class="invoice-header text-center border-bottom pb-4 mb-4">
                        <h1 class="h3 fw-bold text-primary mb-2">Domestic Transfer Receipt</h1>
                        <p class="text-muted mb-1">Reference: {{ $transfer->reference_id }}</p>
                        <p class="text-muted mb-0">Date: {{ $transfer->created_at->format('F j, Y') }}</p>
                    </div>

                    <!-- Notes Section -->
                    <div class="notes-section text-center small text-muted mb-4">
                        <h6 class="fw-semibold">Important Information</h6>
                        <p class="mb-0">
                            Thank you for your transaction. For inquiries, please contact us at {{ config('settings.site.email') }}.<br>
                            This is an automated receipt; no signature is required.
                        </p>
                    </div>

                    <!-- Sender and Recipient Details -->
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="fw-semibold text-uppercase text-primary">From</h6>
                            <p class="mb-1">{{ $transfer->user->first_name }} {{ $transfer->user->last_name }}</p>
                            <p class="mb-1">{{ $transfer->user->profile->account_number }}</p>
                            <p class="mb-1">{{ $transfer->user->profile->account_type ?? 'Savings Account' }}</p>
                            <p class="mb-0">Bank: {{ config('app.name') }}</p>
                        </div>

                        <div class="col-md-6 text-md-end">
                            <h6 class="fw-semibold text-uppercase text-primary">To</h6>
                            <p class="mb-1">{{ $transfer->acct_name }}</p>
                            <p class="mb-1">{{ $transfer->account_number }}</p>
                            <p class="mb-1">{{ $transfer->acct_type }} Account</p>
                            <p class="mb-0">Bank: {{ $transfer->bank_name }}</p>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="mb-5">
                        <h6 class="fw-semibold text-uppercase text-primary mb-3">Transaction Details</h6>
                        <div class="card border border-1 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Reference</span>
                                    <span class="text-muted fs-xs">{{ $transfer->reference_id }}</span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Transaction Date</span>
                                    <span class="text-muted fs-xs">{{ $transfer->created_at->format('F j, Y') }}</span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Amount</span>
                                    <span class="text-muted fs-xs">${{ number_format($transfer->amount, 2) }} USD</span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Currency</span>
                                    <span class="text-muted fs-xs">USD</span>
                                </div>

                                <div class="d-flex justify-content-between py-2">
                                    <span class="fw-light">Purpose</span>
                                    <span class="text-muted fs-xs">{{ $transfer->acct_remarks }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Print Button -->
                    <div class="text-center no-print">
                        <a href="{{ route('user.wallet') }}" class="btn btn-outline-primary px-4">
                            <i class="bi bi-arrow-up-left-circle me-2"></i> Go back
                        </a>
                    </div>
                </div>
            </div> <!-- end inner column -->
        </div> <!-- end row -->
    </div> <!-- end container -->
@endsection

@push('styles')
    <style>
        .invoice-box {
            margin: 0 auto;
        }
        .invoice-header {
            border-bottom-color: #0d6efd !important;
        }

        .fs-xs {
            font-size: 12px !important;
        }
    </style>
@endpush
