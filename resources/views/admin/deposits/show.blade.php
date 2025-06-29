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

        <div class="row justify-content-center min-vh-100">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="invoice-box card border rounded-4 p-4 p-md-5">
                    <!-- Header -->
                    <div class="invoice-header text-center border-bottom pb-4 mb-4">
                        <h1 class="h3 fw-bold text-primary mb-2">Deposit Receipt</h1>
                        <p class="text-muted mb-1">Transaction ID: {{ $deposit->transaction_id }}</p>
                        <p class="text-muted mb-0">Date: {{ $deposit->created_at->format('F j, Y') }}</p>
                    </div>

                    <!-- Notes Section -->
                    <div class="notes-section text-center small text-muted mb-4">
                        <h6 class="fw-semibold">Important Information</h6>
                        <p class="mb-0">
                            Thank you for your deposit. For inquiries, please contact us at {{ config('settings.site.email') }}.<br>
                            This is an automated receipt; no signature is required.
                        </p>
                    </div>

                    <!-- Transaction Details -->
                    <div class="mb-5">
                        <div class="card border border-1 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">User</span>
                                    <span class="text-muted fs-xs">
                                        {{ $deposit->user->first_name }} {{ $deposit->user->last_name }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Transaction Date</span>
                                    <span class="text-muted fs-xs">{{ $deposit->created_at->format('F j, Y') }}</span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Amount</span>
                                    <span class="text-muted fs-xs">$ {{ number_format($deposit->amount, 2) }}</span>
                                </div>

                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-light">Converted Amount</span>
                                    <span class="text-muted fs-xs">{{ number_format((float) str_replace(',', '', $deposit->converted_amount), 6) }} | {{ getWallet($deposit->payment_method) }}</span>
                                </div>

                                <div class="d-flex justify-content-between py-2">
                                    <span class="fw-light">Status</span>
                                    <span class="badge badge-sm badge-light text-bg-{{ $deposit->status == 'approved' ? 'success' : ($deposit->status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                        {{ ucfirst($deposit->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (Approve, Reject, Delete) -->
                    @if($deposit->status == 'pending')
                        <div class="text-center no-print mb-2">
                            <button type="submit" class="btn btn-success text-dark px-4 me-2 rounded-4 mb-3" onclick="approveDeposit({{ $deposit->id }})">
                                <i class="bi bi-check-circle me-2"></i> Approve
                            </button>

                            <button type="submit" class="btn btn-danger px-4 me-2 rounded-4 mb-3" onclick="rejectDeposit({{ $deposit->id }})">
                                <i class="bi bi-x-circle me-2"></i> Reject
                            </button>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="text-center no-print">
                        <a href="{{ route('admin.deposits') }}" class="btn btn-outline-primary px-4 rounded-4">
                            <i class="bi bi-arrow-up-left-circle me-2"></i> Go back
                        </a>
                    </div>
                </div>
            </div> <!-- end inner column -->
        </div> <!-- end row -->
    </div>
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
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const showError = (message) => {
            iziToast.error({...iziToastSettings, message});
        };

        const showSuccess = (message) => {
            iziToast.success({...iziToastSettings, message});
        };

        // Helper function to handle form submission
        function handleDepositForm(form, submitButton, actionUrl, method, successMessage, originalButtonText) {
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            submitButton.disabled = true;

            const formData = new FormData(form);

            fetch(actionUrl, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showSuccess(data.message || successMessage);
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        const errors = Array.isArray(data.message) ? data.message : [data.message || 'An error occurred'];
                        errors.forEach(showError);
                    }
                })
                .catch(error => {
                    showError(error.message || 'Something went wrong. Please try again.');
                })
                .finally(() => {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                });
        }

        // Approve Deposit
        function approveDeposit(deposit_id) {
            const modalElement = document.getElementById('approveDepositModal');
            const form = document.getElementById('approveDepositForm');
            const submitButton = form?.querySelector('button[type="submit"]');

            if (!modalElement || !form || !submitButton) {
                return;
            }

            form.action = `/admin/deposits/${deposit_id}/approve`;
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            // Remove any existing submit listeners
            form.removeEventListener('submit', form._submitHandler);
            form._submitHandler = (event) => {
                event.preventDefault();
                handleDepositForm(form, submitButton, form.action, 'PATCH', 'Deposit approved successfully', '<i class="bi bi-check-circle me-2"></i>Approve');
            };
            form.addEventListener('submit', form._submitHandler);
        }

        // Reject Deposit
        function rejectDeposit(deposit_id) {
            const modalElement = document.getElementById('rejectDepositModal');
            const form = document.getElementById('rejectDepositForm');
            const submitButton = form?.querySelector('button[type="submit"]');

            if (!modalElement || !form || !submitButton) {
                return;
            }

            form.action = `/admin/deposits/${deposit_id}/reject`;
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            // Remove any existing submit listeners
            form.removeEventListener('submit', form._submitHandler);
            form._submitHandler = (event) => {
                event.preventDefault();
                handleDepositForm(form, submitButton, form.action, 'PATCH', 'Deposit rejected successfully', '<i class="bi bi-x-circle me-2"></i>Reject');
            };
            form.addEventListener('submit', form._submitHandler);
        }
    </script>
@endpush
