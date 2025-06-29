@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container my-5" id="main-content">
        <!-- Scattered Star Icons -->
        <div class="position-absolute top-0 start-0 opacity-15" style="z-index: 0;">
            <i class="bi bi-star-fill text-primary" style="font-size: 1.2rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 10%; right: 15%; z-index: 0;">
            <i class="bi bi-stars text-warning" style="font-size: 1.8rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="top: 20%; left: 5%; z-index: 0;">
            <i class="bi bi-star text-info" style="font-size: 1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 35%; right: 5%; z-index: 0;">
            <i class="bi bi-star-fill text-success" style="font-size: 1.5rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 50%; left: 2%; z-index: 0;">
            <i class="bi bi-stars text-purple" style="font-size: 1.3rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="top: 65%; right: 8%; z-index: 0;">
            <i class="bi bi-star text-primary" style="font-size: 1.1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 80%; left: 10%; z-index: 0;">
            <i class="bi bi-star-fill text-warning" style="font-size: 1.4rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="bottom: 20%; right: 12%; z-index: 0;">
            <i class="bi bi-stars text-info" style="font-size: 1.6rem;"></i>
        </div>

        <div class="position-absolute opacity-25" style="bottom: 10%; left: 8%; z-index: 0;">
            <i class="bi bi-star text-success" style="font-size: 1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 45%; right: 20%; z-index: 0;">
            <i class="bi bi-star-fill text-danger" style="font-size: 1.2rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 60%; left: 15%; z-index: 0;">
            <i class="bi bi-stars text-primary" style="font-size: 1.7rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="bottom: 35%; right: 3%; z-index: 0;">
            <i class="bi bi-star text-warning" style="font-size: 1.3rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 25%; left: 20%; z-index: 0;">
            <i class="bi bi-star-fill text-info" style="font-size: 1.1rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="bottom: 50%; left: 3%; z-index: 0;">
            <i class="bi bi-stars text-success" style="font-size: 1.5rem;"></i>
        </div>

        <div class="position-absolute opacity-25" style="top: 15%; right: 25%; z-index: 0;">
            <i class="bi bi-star text-purple" style="font-size: 1rem;"></i>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="invoice-box card border rounded-4 p-4 p-md-5">
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

                                <div class="d-flex justify-content-between py-2">
                                    <span class="fw-light">Status</span>
                                    <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                        {{ ucfirst($transfer->trans_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="text-center no-print">
                        <a href="{{ route('admin.domestic') }}" class="btn btn-outline-primary px-4 rounded-4">
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
