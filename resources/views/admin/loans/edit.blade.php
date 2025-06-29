@extends('layouts.admin')
@section('content')
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.loans') }}">Loans Financing</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Edit Loan</li>
                    </ol>
                </nav>
                <h5>Edit Loan</h5>
            </div>
        </div>

        <form id="edit-loan-form" data-loan-id="{{ $loan->id }}" novalidate>
            @csrf
            @method('PUT')

            <div class="card adminuiux-card overflow-hidden mb-4 rounded-4 border">
                <div class="card-body">
                    <h5 class="mb-3">Loan Request & EMI Calculator</h5>
                    <p class="mb-4 text-secondary">
                        Update the loan details. EMI is calculated automatically.
                        <br>Some fields may be restricted based on loan status.
                    </p>
                    <hr class="mb-4">

                    <div class="row g-4 p-3">
                        <div class="col-12 col-lg-6">
                            <!-- Loan Amount -->
                            <div class="mb-3">
                                <label class="form-label" for="loanAmountInput">Loan Amount</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control rangevalues" id="loanAmountInput" name="loan_amount" min="{{ config('settings.loan.min_amount', 1000) }}" max="{{ config('settings.loan.max_amount', 100000) }}" value="{{ old('loan_amount', $loan->loan_amount) }}" aria-describedby="loanAmountHelp" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid loan amount between ${{ config('settings.loan.min_amount', 1000) }} and ${{ config('settings.loan.max_amount', 100000) }}.
                                    </div>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div id="loanAmountHelp" class="form-text">Enter an amount between ${{ config('settings.loan.min_amount', 1000) }} and ${{ config('settings.loan.max_amount', 100000) }}.</div>
                                <input type="range" class="form-range" id="loanAmountRange" min="{{ config('settings.loan.min_amount', 1000) }}" max="{{ config('settings.loan.max_amount', 100000) }}" value="{{ old('loan_amount', $loan->loan_amount) }}" aria-label="Loan amount range">
                            </div>

                            <!-- Tenure -->
                            <div class="mb-3">
                                <label class="form-label" for="tenureInput">Loan Tenure (Months)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Months</span>
                                    <input type="number" class="form-control rangevalues" id="tenureInput" name="tenure_months" min="1" max="{{ config('settings.loan.repayment_period', 60) }}" value="{{ old('tenure_months', $loan->tenure_months) }}" aria-describedby="tenureHelp" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid tenure between 1 and {{ config('settings.loan.repayment_period', 60) }} months.
                                    </div>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div id="tenureHelp" class="form-text">Select a tenure between 1 and {{ config('settings.loan.repayment_period', 60) }} months.</div>
                                <input type="range" class="form-range" id="tenureRange" min="1" max="{{ config('settings.loan.repayment_period', 60) }}" value="{{ old('tenure_months', $loan->tenure_months) }}" aria-label="Loan tenure range">
                            </div>

                            <!-- Interest Rate -->
                            <div class="mb-3">
                                <label class="form-label" for="interestRateInput">Annual Interest Rate (%)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">%</span>
                                    <input type="number" class="form-control rangevalues" id="interestRateInput" name="interest_rate" step="0.1" min="0" max="50" value="{{ old('interest_rate', $loan->interest_rate) }}" aria-describedby="interestRateHelp" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid interest rate between 0% and 50%.
                                    </div>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div id="interestRateHelp" class="form-text">Enter an interest rate between 0% and 50%.</div>
                                <input type="range" class="form-range" id="interestRateRange" min="0" max="50" step="0.1" value="{{ old('interest_rate', $loan->interest_rate) }}" aria-label="Interest rate range">
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="text-center mb-3 position-relative" style="min-height: 200px;">
                                <canvas id="emiChart" style="max-height: 200px;" aria-label="Pie chart showing principal and interest breakdown"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h4 id="totalPayment" class="mb-0 fw-bold">${{ number_format($loan->total_payment) }}</h4>
                                    <p class="text-muted small">Total Repayment</p>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h6>Your EMI will be</h6>
                                <h1 class="text-theme-1" id="emiResult">${{ number_format($loan->monthly_emi) }} <small class="fs-6 fw-normal">/month</small></h1>
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="text-secondary small mb-1">
                                        <span class="d-inline-block bg-theme-1 rounded-circle me-1" style="width:10px; height:10px;"></span>Principal
                                    </p>
                                    <h5 id="principalAmount">${{ number_format($loan->loan_amount) }}</h5>
                                </div>

                                <div class="col-6">
                                    <p class="text-secondary small mb-1">
                                        <span class="d-inline-block bg-theme-1-subtle rounded-circle me-1" style="width:10px; height:10px;"></span>Interest
                                    </p>
                                    <h5 id="totalInterest">${{ number_format($loan->total_interest) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <h6 class="mb-2">Loan Information</h6>
                    <p class="mb-4 text-secondary small">
                        Loan approval is subject to credit assessment and verification of the information provided in your application.
                        We reserve the right to approve or decline your loan application at our discretion.
                    </p>

                    <div class="row mb-2">
                        <div class="col-6 col-md-6 mb-3">
                            <div class="form-floating mb-3">
                                <input id="title" type="text" class="form-control rounded-4" name="title" value="{{ old('title', $loan->title) }}" required autofocus>
                                <div class="invalid-feedback">Loan title is required.</div>
                                <div class="valid-feedback">Looks good!</div>
                                <label for="title">Loan Title</label>
                            </div>
                        </div>

                        <div class="col-6 col-md-6 mb-3">
                            <div class="form-floating mb-4">
                                <select class="form-select rounded-4" id="status" name="status" required>
                                    <option value="" disabled {{ old('status', $loan->status) ? '' : 'selected' }}>Select Status</option>
                                    <option value="approved" {{ old('status', $loan->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ old('status', $loan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ old('status', $loan->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="disbursed" {{ old('status', $loan->status) == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                                    <option value="completed" {{ old('status', $loan->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid status.</div>
                                <div class="valid-feedback">Looks good!</div>
                                <label for="status">Status</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <textarea id="loan_reason" rows="4" name="loan_reason" class="form-control rounded-4" placeholder="Loan Reason" required>{{ old('loan_reason', $loan->loan_reason) }}</textarea>
                            <div class="invalid-feedback">Loan reason is required.</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <textarea id="loan_collateral" rows="4" name="loan_collateral" class="form-control rounded-4" placeholder="Collateral Information" required>{{ old('loan_collateral', $loan->loan_collateral) }}</textarea>
                            <div class="invalid-feedback">Collateral information is required.</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" name="monthly_emi" id="monthlyEmiInput" value="{{ $loan->monthly_emi }}">
                    <input type="hidden" name="total_interest" id="totalInterestInput" value="{{ $loan->total_interest }}">
                    <input type="hidden" name="total_payment" id="totalPaymentInput" value="{{ $loan->total_payment }}">
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                <label class="form-check-label" for="confirmCheck">
                    I confirm that all provided information is accurate.
                </label>
                <div class="invalid-feedback">You must confirm the accuracy of the provided information.</div>
            </div>

            <div class="row mb-4">
                <div class="col-auto">
                    <button type="submit" id="updateLoan" class="btn btn-theme">
                        <span id="buttonText">Update Loan</span>
                        <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
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

        const loanAmountInput = document.getElementById('loanAmountInput');
        const loanAmountRange = document.getElementById('loanAmountRange');
        const tenureInput = document.getElementById('tenureInput');
        const tenureRange = document.getElementById('tenureRange');
        const interestRateInput = document.getElementById('interestRateInput');
        const interestRateRange = document.getElementById('interestRateRange');
        const emiResult = document.getElementById('emiResult');
        const totalInterestEl = document.getElementById('totalInterest');
        const totalPaymentEl = document.getElementById('totalPayment');
        const principalAmountEl = document.getElementById('principalAmount');
        const monthlyEmiInput = document.getElementById('monthlyEmiInput');
        const totalInterestInput = document.getElementById('totalInterestInput');
        const totalPaymentInput = document.getElementById('totalPaymentInput');
        const editLoanForm = document.getElementById('edit-loan-form');
        const updateButton = document.getElementById('updateLoan');
        const buttonText = document.getElementById('buttonText');
        const buttonSpinner = document.getElementById('buttonSpinner');

        let chartInstance;

        function debounce(fn, delay) {
            let timeout;
            return function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, arguments), delay);
            };
        }

        function syncInputAndRange(input, range) {
            input.addEventListener('input', () => {
                range.value = input.value;
                validateField(input);
                triggerDebouncedUpdate();
            });

            range.addEventListener('input', () => {
                input.value = range.value;
                validateField(input);
                triggerDebouncedUpdate();
            });
        }

        function validateField(field) {
            const value = field.value.trim();
            let isValid;

            // Remove previous validation classes
            field.classList.remove('is-valid', 'is-invalid');

            switch (field.id) {
                case 'loanAmountInput':
                    const amount = parseFloat(value);
                    const minAmount = {{ config('settings.loan.min_amount', 1000) }};
                    const maxAmount = {{ config('settings.loan.max_amount', 100000) }};
                    isValid = !isNaN(amount) && amount >= minAmount && amount <= maxAmount;
                    break;

                case 'tenureInput':
                    const tenure = parseInt(value);
                    const maxTenure = {{ config('settings.loan.repayment_period', 60) }};
                    isValid = !isNaN(tenure) && tenure >= 1 && tenure <= maxTenure;
                    break;

                case 'interestRateInput':
                    const rate = parseFloat(value);
                    isValid = !isNaN(rate) && rate >= 0 && rate <= 50;
                    break;

                case 'title':
                case 'loan_reason':
                case 'loan_collateral':
                    isValid = value.length > 0;
                    break;

                case 'status':
                    isValid = value !== '';
                    break;

                case 'confirmCheck':
                    isValid = field.checked;
                    break;

                default:
                    isValid = field.checkValidity();
            }

            // Add appropriate validation class
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');

            return isValid;
        }

        function validateAllFields() {
            const fields = [
                loanAmountInput,
                tenureInput,
                interestRateInput,
                document.getElementById('title'),
                document.getElementById('status'),
                document.getElementById('loan_reason'),
                document.getElementById('loan_collateral'),
                document.getElementById('confirmCheck')
            ];

            let allValid = true;

            fields.forEach(field => {
                if (!validateField(field)) {
                    allValid = false;
                }
            });

            return allValid;
        }

        // Add real-time validation to all form fields
        document.addEventListener('DOMContentLoaded', function() {
            const formFields = editLoanForm.querySelectorAll('input, select, textarea');

            formFields.forEach(field => {
                field.addEventListener('blur', () => validateField(field));
                field.addEventListener('input', () => {
                    // Remove invalid class immediately when the user starts typing
                    if (field.classList.contains('is-invalid')) {
                        field.classList.remove('is-invalid');
                    }
                });
                field.addEventListener('change', () => validateField(field));
            });
        });

        syncInputAndRange(loanAmountInput, loanAmountRange);
        syncInputAndRange(tenureInput, tenureRange);
        syncInputAndRange(interestRateInput, interestRateRange);

        const triggerDebouncedUpdate = debounce(calculateAndRender, 300);

        function formatNumberAbbreviation(num) {
            if (!num || isNaN(num)) return 'N/A';
            if (num >= 1_000_000) {
                return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
            } else if (num >= 1_000) {
                return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'k';
            } else {
                return num.toLocaleString();
            }
        }

        function calculateAndRender() {
            const principal = parseFloat(loanAmountInput.value);
            const rate = parseFloat(interestRateInput.value);
            const tenure = parseInt(tenureInput.value);

            if (!principal || !rate || !tenure) return;

            const monthlyRate = rate / 100 / 12;
            const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, tenure)) /
                (Math.pow(1 + monthlyRate, tenure) - 1);

            const totalPayment = emi * tenure;
            const totalInterest = totalPayment - principal;

            emiResult.innerHTML = `$${formatNumberAbbreviation(emi.toFixed(2))} <small class="fs-6 fw-normal">/month</small>`;
            totalInterestEl.textContent = `$${formatNumberAbbreviation(totalInterest.toFixed(2))}`;
            totalPaymentEl.textContent = `$${formatNumberAbbreviation(totalPayment.toFixed(2))}`;
            principalAmountEl.textContent = `$${formatNumberAbbreviation(principal)}`;

            monthlyEmiInput.value = emi.toFixed(2);
            totalInterestInput.value = totalInterest.toFixed(2);
            totalPaymentInput.value = totalPayment.toFixed(2);

            drawChart(principal, totalInterest);
        }

        function drawChart(principal, interest) {
            const ctx = document.getElementById('emiChart').getContext('2d');
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Principal', 'Interest'],
                    datasets: [{
                        data: [principal, interest],
                        backgroundColor: ['#00725b', '#960028']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {position: 'bottom'},
                        title: {display: false}
                    }
                }
            });
        }

        function showLoading(show = true) {
            if (show) {
                updateButton.disabled = true;
                buttonSpinner.classList.remove('d-none');
                buttonText.textContent = 'Processing...';
            } else {
                updateButton.disabled = false;
                buttonText.textContent = 'Update Loan';
                buttonSpinner.classList.add('d-none');
            }
        }

        // Form validation and AJAX submission
        editLoanForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Validate all fields
            if (!validateAllFields()) {
                editLoanForm.classList.add('was-validated');
                return;
            }

            showLoading(true);

            const formData = new FormData(this);
            const loanId = this.dataset.loanId;

            try {
                const response = await fetch(`/admin/loans/${loanId}/update`, {
                    method: 'POST', // Laravel handles PUT via _method
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Success handling
                    showSuccess(data.message || 'Loan updated successfully!');

                    // Redirect after a short delay to show the success message
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.loans") }}';
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.remove('is-valid');
                                input.classList.add('is-invalid');

                                // Find and update the error message
                                let errorDiv = input.parentElement.querySelector('.invalid-feedback');
                                if (!errorDiv) {
                                    errorDiv = input.closest('.form-floating, .input-group, .form-check')?.querySelector('.invalid-feedback');
                                }
                                if (!errorDiv) {
                                    errorDiv = input.nextElementSibling;
                                    if (errorDiv && !errorDiv.classList.contains('invalid-feedback')) {
                                        errorDiv = errorDiv.nextElementSibling;
                                    }
                                }

                                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                    errorDiv.textContent = data.errors[field][0];
                                }
                            }
                        });

                        editLoanForm.classList.add('was-validated');
                        showError('Please correct the validation errors and try again.');
                    } else {
                        showError(data.message || 'An error occurred while updating the loan.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showError('An unexpected error occurred. Please check your connection and try again.');
            } finally {
                showLoading(false);
            }
        });

        window.addEventListener('DOMContentLoaded', function() {
            calculateAndRender();
            // Initial validation of pre-filled fields
            setTimeout(() => {
                const preFilledFields = editLoanForm.querySelectorAll('input[value], select option[selected], textarea');
                preFilledFields.forEach(field => {
                    if (field.value && field.value.trim() !== '') {
                        validateField(field);
                    }
                });
            }, 100);
        });
    </script>
@endpush
