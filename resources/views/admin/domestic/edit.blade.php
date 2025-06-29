@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.domestic') }}">Domestic Transfers</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Update Transfer</li>
                    </ol>
                </nav>
                <h5>Update Transfer</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <!-- Domestic Transfer Form Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card h-100 rounded-4 border">
                            <div class="card-header">
                                <h6>Update Domestic Transfer Details</h6>
                            </div>

                            <div class="card-body">
                                <form id="domestic-transfer" action="{{ route('admin.domestic.update', $transfer->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('sender') is-invalid @enderror" id="sender" name="sender">
                                                    <option value="" disabled>Select Sender</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}"
                                                                {{ (old('sender') ?? $transfer->user_id) == $user->id ? 'selected' : '' }}
                                                                data-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="sender_error">Sender is required.</div>
                                                <label for="sender">Sender</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('acct_name') is-invalid @enderror" id="acct_name" name="acct_name" autofocus value="{{ old('acct_name') ?? $transfer->acct_name }}">
                                                <div class="invalid-feedback" id="acct_name_error">Receiver Name is required.</div>
                                                <label for="acct_name">Receiver Name</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('account_number') is-invalid @enderror" name="account_number" id="account_number" value="{{ old('account_number') ?? $transfer->account_number }}">
                                                <div class="invalid-feedback" id="account_number_error">Account Number is required and must be numeric.</div>
                                                <label for="account_number">Account Number</label>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <button type="button" class="btn btn-square btn-theme mt-2"><i class="bi bi-arrow-left-right"></i></button>
                                        </div>

                                        <div class="col">
                                            <div class="form-floating mb-1">
                                                <input type="text" class="form-control rounded-4 @error('amount') is-invalid @enderror" name="amount" id="domestic_transfer_amount" value="{{ old('amount') ?? $transfer->amount }}">
                                                <div class="invalid-feedback" id="amount_error">Amount is required and must be a valid number.</div>
                                                <label for="domestic_transfer_amount">Amount</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mb-5">
                                        <h5 class="fw-normal">
                                            <b class="fw-bold">Great!</b> You are about to send
                                        </h5>
                                        <h1 class="mb-0 text-theme-1" id="confirm-amount">${{ number_format($transfer->amount, 2) }}</h1>
                                        <p class="text-secondary small">from <span id="sender-name">{{ $transfer->user->first_name ?? '-' }} {{ $transfer->user->last_name ?? '-' }}</span> to <span id="receiver-name">{{ $transfer->acct_name ?? '-' }}</span></p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('bank_name') is-invalid @enderror" name="bank_name" id="bank_name" value="{{ old('bank_name') ?? $transfer->bank_name }}">
                                                <div class="invalid-feedback" id="bank_name_error">Bank Name is required.</div>
                                                <label for="bank_name">Bank Name</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('acct_type') is-invalid @enderror" id="acct_type" name="acct_type">
                                                    <option value="" disabled>Select Account Type</option>
                                                    <option value="Savings" {{ (old('acct_type') ?? $transfer->acct_type) == 'Savings' ? 'selected' : '' }}>Savings Account</option>
                                                    <option value="Current" {{ (old('acct_type') ?? $transfer->acct_type) == 'Current' ? 'selected' : '' }}>Current Account</option>
                                                    <option value="Checking" {{ (old('acct_type') ?? $transfer->acct_type) == 'Checking' ? 'selected' : '' }}>Checking Account</option>
                                                    <option value="Fixed Deposit" {{ (old('acct_type') ?? $transfer->acct_type) == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                                    <option value="Non Resident" {{ (old('acct_type') ?? $transfer->acct_type) == 'Non Resident' ? 'selected' : '' }}>Non Resident Account</option>
                                                    <option value="Online Banking" {{ (old('acct_type') ?? $transfer->acct_type) == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                                    <option value="Domiciliary Account" {{ (old('acct_type') ?? $transfer->acct_type) == 'Domiciliary Account' ? 'selected' : '' }}>Domiciliary Account</option>
                                                    <option value="Joint Account" {{ (old('acct_type') ?? $transfer->acct_type) == 'Joint Account' ? 'selected' : '' }}>Joint Account</option>
                                                </select>
                                                <div class="invalid-feedback" id="acct_type_error">Account Type is required.</div>
                                                <label for="acct_type">Account Type</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('trans_status') is-invalid @enderror" id="trans_status" name="trans_status">
                                                    <option value="" disabled>Select Status</option>
                                                    <option value="approved" {{ (old('trans_status') ?? $transfer->trans_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="pending" {{ (old('trans_status') ?? $transfer->trans_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="rejected" {{ (old('trans_status') ?? $transfer->trans_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                                <div class="invalid-feedback" id="trans_status_error">Status is required.</div>
                                                <label for="trans_status">Status</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="date" class="form-control rounded-4 @error('date') is-invalid @enderror" name="date" id="transaction_date" value="{{ old('date') ?? $transfer->created_at->format('Y-m-d') }}">
                                                <div class="invalid-feedback" id="transaction_date_error">Transaction date is required.</div>
                                                <label for="transaction_date">Transaction Date</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <textarea class="form-control rounded-4 @error('acct_remarks') is-invalid @enderror" name="acct_remarks" id="acct_remarks" rows="3" placeholder="Transfer Description">{{ old('acct_remarks') ?? $transfer->acct_remarks }}</textarea>
                                            <div class="invalid-feedback" id="acct_remarks_error">Transfer Description is required.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <button type="submit" id="domesticBtn" class="btn btn-theme w-100 rounded-4">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="button-text">Update Transfer</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("domestic-transfer");
            const amountInput = document.getElementById("domestic_transfer_amount");
            const senderSelect = document.getElementById("sender");
            const nameInput = document.getElementById("acct_name");
            const accountNumberInput = document.getElementById("account_number");
            const bankNameInput = document.getElementById("bank_name");
            const acctTypeSelect = document.getElementById("acct_type");
            const transStatusSelect = document.getElementById("trans_status");
            const transactionDateInput = document.getElementById("transaction_date");
            const remarksInput = document.getElementById("acct_remarks");
            const submitButton = document.getElementById("domesticBtn");
            const confirmAmount = document.getElementById("confirm-amount");
            const senderName = document.getElementById("sender-name");
            const receiverName = document.getElementById("receiver-name");

            // Format amount for display
            function formatAmount(value) {
                let num = parseFloat(value.replace(/,/g, ''));
                return isNaN(num) ? "0.00" : num.toFixed(2);
            }

            // Update confirmation text
            amountInput.addEventListener("input", () => {
                confirmAmount.textContent = `$${formatAmount(amountInput.value)}`;
            });

            senderSelect.addEventListener("change", () => {
                const selectedOption = senderSelect.options[senderSelect.selectedIndex];
                senderName.textContent = selectedOption ? selectedOption.getAttribute('data-name') || "-" : "-";
            });

            nameInput.addEventListener("input", () => {
                receiverName.textContent = nameInput.value || "-";
            });

            // Form validation
            function validateForm() {
                let isValid = true;

                // Reset invalid feedback
                document.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(error => error.style.display = 'none');

                // Sender validation
                if (!senderSelect.value) {
                    senderSelect.classList.add('is-invalid');
                    document.getElementById('sender_error').style.display = 'block';
                    isValid = false;
                }

                // Receiver name validation
                if (!nameInput.value.trim()) {
                    nameInput.classList.add('is-invalid');
                    document.getElementById('acct_name_error').style.display = 'block';
                    isValid = false;
                }

                // Account number validation
                if (!accountNumberInput.value.trim() || isNaN(accountNumberInput.value.replace(/,/g, ''))) {
                    accountNumberInput.classList.add('is-invalid');
                    document.getElementById('account_number_error').style.display = 'block';
                    isValid = false;
                }

                // Amount validation
                const amount = parseFloat(amountInput.value.replace(/,/g, ''));
                if (!amountInput.value.trim() || isNaN(amount) || amount <= 0) {
                    amountInput.classList.add('is-invalid');
                    document.getElementById('amount_error').style.display = 'block';
                    isValid = false;
                }

                // Bank name validation
                if (!bankNameInput.value.trim()) {
                    bankNameInput.classList.add('is-invalid');
                    document.getElementById('bank_name_error').style.display = 'block';
                    isValid = false;
                }

                // Account type validation
                if (!acctTypeSelect.value) {
                    acctTypeSelect.classList.add('is-invalid');
                    document.getElementById('acct_type_error').style.display = 'block';
                    isValid = false;
                }

                // Transaction status validation
                if (!transStatusSelect.value) {
                    transStatusSelect.classList.add('is-invalid');
                    document.getElementById('trans_status_error').style.display = 'block';
                    isValid = false;
                }

                // Transaction date validation
                if (!transactionDateInput.value) {
                    transactionDateInput.classList.add('is-invalid');
                    document.getElementById('transaction_date_error').style.display = 'block';
                    isValid = false;
                }

                // Remarks validation
                if (!remarksInput.value.trim()) {
                    remarksInput.classList.add('is-invalid');
                    document.getElementById('acct_remarks_error').style.display = 'block';
                    isValid = false;
                }

                return isValid;
            }

            // Form submission via AJAX
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!validateForm()) {
                    return;
                }

                const submitButtonSpinner = submitButton.querySelector('.spinner-border');
                const submitButtonText = submitButton.querySelector('.button-text');
                submitButton.disabled = true;
                submitButtonSpinner.classList.remove('d-none');
                submitButtonText.textContent = 'Updating...';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showSuccess(data.message || 'Transfer updated successfully');
                            setTimeout(() => location.href = "{{ route('admin.domestic') }}", 2000);
                        } else {
                            showError(data.message || 'Failed to update transfer. Please try again.');
                        }
                    })
                    .catch(error => {
                        showError(error || 'Something went wrong. Please try again.');
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButtonSpinner.classList.add('d-none');
                        submitButtonText.textContent = 'Update Transfer';
                    });
            });
        });
    </script>
@endpush
