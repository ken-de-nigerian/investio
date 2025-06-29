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
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.deposits') }}">Deposits</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.alert') }}">Credit | Debit</a></li>
                        <li class="breadcrumb-item bi active" aria-current="page">Edit Alert</li>
                    </ol>
                </nav>
                <h5>Edit Credit | Debit Alert</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <!-- Edit Alert Form Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card h-100 rounded-4 border">
                            <div class="card-header">
                                <h6>Edit Credit | Debit Alert Details</h6>
                            </div>

                            <div class="card-body">
                                <form id="alert-form" action="{{ route('admin.alert.update', $alert->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('sender_name') is-invalid @enderror" id="sender_name" name="sender_name" autofocus value="{{ old('sender_name', $alert->sender_name) }}">
                                                <div class="invalid-feedback" id="sender_name_error">Sender Name is required.</div>
                                                <label for="sender_name">Sender Name</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('sender_bank') is-invalid @enderror" name="sender_bank" id="sender_bank" value="{{ old('sender_bank', $alert->sender_bank) }}">
                                                <div class="invalid-feedback" id="sender_bank_error">Sender Bank is required.</div>
                                                <label for="sender_bank">Sender Bank</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('receiver') is-invalid @enderror" id="receiver" name="receiver">
                                                    <option value="" disabled {{ old('receiver', $alert->user_id) ? '' : 'selected' }}>Select Receiver</option>
                                                    @foreach($users->where('role', '!=', 'admin') as $user)
                                                        <option value="{{ $user->id }}" {{ old('receiver', $alert->user_id) == $user->id ? 'selected' : '' }} data-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="receiver_error">Receiver is required.</div>
                                                <label for="receiver">Receiver</label>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <button type="button" class="btn btn-square btn-theme mt-2"><i class="bi bi-arrow-left-right"></i></button>
                                        </div>

                                        <div class="col">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('amount') is-invalid @enderror" name="amount" id="alert_amount" value="{{ old('amount', $alert->amount) }}" onkeyup="this.value = this.value.replace(/^\.|[^\d.]/g, '')">
                                                <div class="invalid-feedback" id="amount_error">Amount is required and must be a valid number.</div>
                                                <label for="alert_amount">Amount</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mb-5">
                                        <h5 class="fw-normal">
                                            <b class="fw-bold">Great!</b> You are about to <span id="trans-action">{{ $alert->trans_type ?? 'update an alert' }}</span>
                                        </h5>
                                        <h1 class="mb-0 text-theme-1" id="confirm-amount">${{ number_format($alert->amount, 2) }}</h1>
                                        <p class="text-secondary small">to <span id="confirm-name">{{ $alert->user ? $alert->user->first_name . ' ' . $alert->user->last_name : 'Select a receiver' }}</span></p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('trans_type') is-invalid @enderror" id="trans_type" name="trans_type">
                                                    <option value="" disabled {{ old('trans_type', $alert->trans_type) ? '' : 'selected' }}>Select Transaction Type</option>
                                                    <option value="credit" {{ old('trans_type', $alert->trans_type) == 'credit' ? 'selected' : '' }}>Credit</option>
                                                    <option value="debit" {{ old('trans_type', $alert->trans_type) == 'debit' ? 'selected' : '' }}>Debit</option>
                                                </select>
                                                <div class="invalid-feedback" id="trans_type_error">Transaction Type is required.</div>
                                                <label for="trans_type">Transaction Type</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('status') is-invalid @enderror" id="status" name="status">
                                                    <option value="" disabled {{ old('status', $alert->status) ? '' : 'selected' }}>Select Status</option>
                                                    <option value="approved" {{ old('status', $alert->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="pending" {{ old('status', $alert->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="rejected" {{ old('status', $alert->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                                <div class="invalid-feedback" id="status_error">Status is required.</div>
                                                <label for="status">Status</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-4">
                                                <input type="date" class="form-control rounded-4 @error('date') is-invalid @enderror" name="date" id="transaction_date" value="{{ old('date', $alert->date->format('Y-m-d')) }}">
                                                <div class="invalid-feedback" id="transaction_date_error">Transaction date is required.</div>
                                                <label for="transaction_date">Transaction Date</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <button type="submit" id="alertBtn" class="btn btn-theme w-100 rounded-4">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="button-text">Update Alert</span>
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
            const amountInput = document.getElementById("alert_amount");
            const receiverSelect = document.getElementById("receiver");
            const confirmAmount = document.getElementById("confirm-amount");
            const confirmName = document.getElementById("confirm-name");
            const form = document.getElementById("alert-form");
            const senderNameInput = document.getElementById("sender_name");
            const senderBankInput = document.getElementById("sender_bank");
            const transTypeSelect = document.getElementById("trans_type");
            const statusSelect = document.getElementById("status");
            const transAction = document.getElementById("trans-action");
            const transactionDateInput = document.getElementById("transaction_date");
            const submitButton = document.getElementById("alertBtn");
            const spinner = submitButton.querySelector(".spinner-border");
            const buttonText = submitButton.querySelector(".button-text");

            function formatAmount(value) {
                let num = parseFloat(value.replace(/,/g, ''));
                return isNaN(num) ? "0.00" : num.toFixed(2);
            }

            // Update amount display
            amountInput.addEventListener("input", () => {
                confirmAmount.textContent = `$${formatAmount(amountInput.value)}`;
            });

            // Update receiver name
            receiverSelect.addEventListener("change", () => {
                const selectedOption = receiverSelect.options[receiverSelect.selectedIndex];
                confirmName.textContent = selectedOption.dataset.name || "Select a receiver";
            });

            // Update transaction action (credit/debit)
            transTypeSelect.addEventListener("change", () => {
                transAction.textContent = transTypeSelect.value === "credit" ? "credit" : transTypeSelect.value === "debit" ? "debit" : "update an alert";
            });

            // Trigger initial updates
            if (receiverSelect.value) {
                const selectedOption = receiverSelect.options[receiverSelect.selectedIndex];
                confirmName.textContent = selectedOption.dataset.name || "Select a receiver";
            }
            if (transTypeSelect.value) {
                transAction.textContent = transTypeSelect.value;
            }

            // Form validation
            function validateForm() {
                let isValid = true;
                const errors = {
                    sender_name: "Please enter the sender's name (at least 2 characters)",
                    sender_bank: "Please enter the sender's bank (at least 2 characters)",
                    receiver: "Please select a receiver",
                    amount: "Please enter a valid amount greater than 0",
                    trans_type: "Please select a transaction type",
                    status: "Please select a status",
                    date: "Please select a valid transaction date"
                };

                // Reset invalid states
                [senderNameInput, senderBankInput, receiverSelect, amountInput, transTypeSelect, statusSelect, transactionDateInput].forEach(input => {
                    input.classList.remove("is-invalid");
                    const errorElement = document.getElementById(`${input.id}_error`);
                    if (errorElement) errorElement.textContent = "";
                });

                // Validate sender name
                if (!senderNameInput.value.trim() || senderNameInput.value.length < 2) {
                    senderNameInput.classList.add("is-invalid");
                    document.getElementById("sender_name_error").textContent = errors.sender_name;
                    isValid = false;
                }

                // Validate sender bank
                if (!senderBankInput.value.trim() || senderBankInput.value.length < 2) {
                    senderBankInput.classList.add("is-invalid");
                    document.getElementById("sender_bank_error").textContent = errors.sender_bank;
                    isValid = false;
                }

                // Validate receiver
                if (!receiverSelect.value) {
                    receiverSelect.classList.add("is-invalid");
                    document.getElementById("receiver_error").textContent = errors.receiver;
                    isValid = false;
                }

                // Validate amount
                const amountValue = parseFloat(amountInput.value);
                if (isNaN(amountValue) || amountValue <= 0) {
                    amountInput.classList.add("is-invalid");
                    document.getElementById("amount_error").textContent = errors.amount;
                    isValid = false;
                }

                // Validate transaction type
                if (!transTypeSelect.value) {
                    transTypeSelect.classList.add("is-invalid");
                    document.getElementById("trans_type_error").textContent = errors.trans_type;
                    isValid = false;
                }

                // Validate status
                if (!statusSelect.value) {
                    statusSelect.classList.add("is-invalid");
                    document.getElementById("status_error").textContent = errors.status;
                    isValid = false;
                }

                // Validate transaction date
                if (!transactionDateInput.value) {
                    transactionDateInput.classList.add("is-invalid");
                    document.getElementById("transaction_date_error").textContent = errors.date;
                    isValid = false;
                }

                return isValid;
            }

            // Form submission
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Show spinner, hide button text
                spinner.classList.remove("d-none");
                buttonText.classList.add("d-none");
                submitButton.disabled = true;

                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    const response = await fetch(form.action, {
                        method: "PUT",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)
                    });

                    if (response.ok) {
                        showSuccess("Alert updated successfully!");
                        form.reset();
                        confirmAmount.textContent = "$0.00";
                        confirmName.textContent = "Select a receiver";
                        transAction.textContent = "update an alert";
                        setTimeout(() => window.location.href = "{{ route('admin.alert') }}", 3000);
                    } else {
                        const errorData = await response.json();
                        let errorMessage = "An error occurred while updating the alert.";
                        if (errorData.errors) {
                            errorMessage = Object.values(errorData.errors).flat().join("<br>");
                        } else if (errorData.message) {
                            errorMessage = errorData.message;
                        }
                        showError(errorMessage);
                    }
                } catch (error) {
                    showError("An error occurred while updating the alert.");
                } finally {
                    // Hide spinner, show button text
                    spinner.classList.add("d-none");
                    buttonText.classList.remove("d-none");
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endpush
