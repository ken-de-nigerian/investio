@extends('layouts.admin')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Loans Financing</li>
                    </ol>
                </nav>
                <h5>Loans Financing</h5>
            </div>
        </div>

        <div class="row">
            <!-- loans -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card adminuiux-card mb-4 border rounded-4">
                    <div class="card-body z-index-1">
                        <div class="avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded mb-3"><i class="bi bi-bank h4"></i></div>
                        <h4 class="fw-medium">${{ number_format($active_sum, 2) }}</h4>
                        <p>
                            <span class="text-secondary">Active loan:</span> <b>{{ $active_count }} Loans</b>
                            <small class="text-secondary">in last 1mo.</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- amount disbursed -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card adminuiux-card theme-teal mb-4 border rounded-4">
                    <div class="card-body z-index-1">
                        <div class="avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded mb-3"><i class="bi bi-cash-coin h4"></i></div>
                        <h4 class="fw-medium">${{ number_format($disbursed_sum, 2) }}</h4>
                        <p>
                            <span class="text-secondary">Disbursed:</span> <b>{{ $disbursed_count }} Loans</b>
                            <small class="text-secondary">in last 1mo.</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- pending -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card adminuiux-card theme-orange mb-4 border rounded-4">
                    <div class="card-body z-index-1">
                        <div class="avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded mb-3"><i class="bi bi-houses h4"></i></div>
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="fw-medium">{{ $pending }} <small class="text-secondary fw-normal">loans</small></h4>
                                <p><span class="text-secondary">Pending applications</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- rejected -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card adminuiux-card theme-red mb-4 border rounded-4">
                    <div class="card-body z-index-1">
                        <div class="avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded mb-3"><i
                                class="bi bi-file-earmark-check h4"></i></div>
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="fw-medium">{{ $rejected }} <small class="text-secondary fw-normal">loans</small></h4>
                                <p><span class="text-secondary">Rejected applications</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMI Transaction -->
            <div class="col-12 col-md-12 col-xl-12 col-xxl-12 mb-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Recent Loan Requests</h6>
                            </div>

                            <div class="col-auto">
                                <select class="form-select form-select-sm rounded-pill border" id="sortSelectLoan" onchange="sortLoan('loan', this.value)">
                                    <option value="" {{ request()->query('sort') == '' ? 'selected' : '' }}>Sort By</option>
                                    <option value="approved" {{ request()->query('sort') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request()->query('sort') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request()->query('sort') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="disbursed" {{ request()->query('sort') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                                    <option value="rejected" {{ request()->query('sort') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover transfers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Monthly EMI</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Next Due</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($loans as $loan)
                                    @php

                                        $baseDate = $loan->created_at;
                                        $paidEmi = $loan->paid_emi ?? 0;
                                        $repaymentPeriod = $loan->tenure_months;
                                        $nextEmiNumber = $paidEmi + 1;
                                        $isLoanCompleted = $paidEmi >= $repaymentPeriod;


                                        if (!$isLoanCompleted) {
                                            $nextDueDate = $baseDate->copy()->addMonths($nextEmiNumber);
                                            $status = 'Upcoming';
                                            $badge = 'warning';
                                            $shouldStrike = now()->gt($nextDueDate);
                                        } else {
                                            $nextDueDate = $baseDate->copy()->addMonths($repaymentPeriod);
                                            $status = 'Completed';
                                            $badge = 'success';
                                            $shouldStrike = true;
                                        }
                                    @endphp
                                    <tr>
                                        <td data-label="User">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $loan->user->first_name }} {{ $loan->user->last_name }}</p>
                                            <p class="small text-theme-1">
                                                {{ $loan->user->profile->account_number }}
                                            </p>
                                        </td>

                                        <td data-label="Title">
                                            <p class="mb-0 small" style="font-size: 12px;">@truncate($loan->title, 25)</p>
                                        </td>

                                        <td data-label="Amount">
                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($loan->loan_amount, 2) }} ({{ number_format($loan->total_interest, 2) }} interest)</small>
                                            <p class="small text-theme-1">
                                                Repayment : ${{ number_format($loan->total_payment, 2) }}
                                            </p>
                                        </td>

                                        <td data-label="Monthly EMI">
                                            <span class="badge badge-sm badge-light text-bg-secondary small" style="font-size: 12px;">
                                                ${{ number_format($loan->monthly_emi ?? 0, 2) }}
                                            </span>
                                        </td>

                                        <td data-label="Status">
                                            @php
                                                // Use loan status for badge instead of calculated status
                                                $statusBadges = [
                                                    'pending' => 'text-bg-warning',
                                                    'approved' => $isLoanCompleted ? 'text-bg-success' : 'text-bg-secondary',
                                                    'rejected' => 'text-bg-danger',
                                                    'disbursed' => 'text-bg-info',
                                                    'completed' => 'text-bg-success',
                                                ];
                                                $badgeClass = $statusBadges[$loan->status] ?? 'text-bg-secondary';
                                                $displayStatus = $isLoanCompleted ? 'Completed' : ucfirst($loan->status);
                                            @endphp
                                            <span class="badge badge-sm badge-light {{ $badgeClass }}" style="font-size: 12px;">
                                                {{ $displayStatus }}
                                            </span>
                                        </td>

                                        <td data-label="Next Due">
                                            <small class="fw-normal small" style="font-size: 12px;">
                                                @if($isLoanCompleted)
                                                    Loan completed on: {{ $nextDueDate->format('jS F Y') }}
                                                @else
                                                    Next due date: {{ $nextDueDate->format('jS F Y') }}
                                                @endif
                                            </small>
                                            <p class="small text-theme-1">
                                                Paid : {{ $loan->paid_emi }}/{{ $loan->tenure_months }}
                                            </p>
                                        </td>

                                        <td data-label="Action">
                                            <a href="{{ route('admin.loan.show', $loan->id) }}" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                            <a href="{{ route('admin.loan.edit', $loan->id) }}" class="btn btn-sm btn-outline-primary rounded-4">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteLoan({{ $loan->id }})">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No loans found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $loans->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const showError = (message) => {
            iziToast.error({...iziToastSettings, message});
        };

        const showSuccess = (message) => {
            iziToast.success({...iziToastSettings, message});
        };

        function sortLoan(type, status) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', status);
            window.location.href = url.toString();
        }

        // Helper function to handle form submission
        function handleDeleteForm(form, submitButton, actionUrl, successMessage) {
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            submitButton.disabled = true;

            const formData = new FormData(form);

            fetch(actionUrl, {
                method: "DELETE",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showSuccess(data.message || successMessage);
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        const errors = Array.isArray(data.message) ? data.message : [data.message];
                        errors.forEach(showError);
                    }
                })
                .catch(error => {
                    showError(error || "Something went wrong. Please try again.");
                })
                .finally(() => {
                    submitButton.innerHTML = 'Delete';
                    submitButton.disabled = false;
                });
        }

        // Delete Loan
        function deleteLoan(loan_id) {
            const modalElement = document.getElementById('deleteLoanModal');
            const form = document.getElementById('deleteLoanForm');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;

            if (!form || !submitButton) {
                return;
            }

            form.action = `/admin/loans/${loan_id}/delete`;
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            form.removeEventListener('submit', form._submitHandler);
            form._submitHandler = (event) => {
                event.preventDefault();
                handleDeleteForm(form, submitButton, form.action, "Loan deleted successfully");
            };
            form.addEventListener('submit', form._submitHandler);
        }
    </script>
@endpush
