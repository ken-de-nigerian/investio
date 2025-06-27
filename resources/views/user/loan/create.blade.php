@extends('layouts.app')
@section('content')
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.loan') }}">My Loans</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Request Loan</li>
                    </ol>
                </nav>
                <h5>Request Loan</h5>
            </div>
        </div>

        <form id="request-loan-form">
            @csrf

            <div class="card adminuiux-card overflow-hidden mb-4 rounded-4 border">
                <div class="card-body">
                    <h5 class="mb-3">Loan Request & EMI Calculator</h5>
                    <p class="mb-4 text-secondary">
                        Fill in your desired loan details. EMI is calculated automatically.
                        <br>You cannot modify this information after submission.
                    </p>
                    <hr class="mb-4">

                    <div class="row g-4 p-3">
                        <div class="col-12 col-lg-6">
                            <!-- Loan Amount -->
                            <div class="mb-3">
                                <label class="form-label" for="loanAmountInput">Loan Amount</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control rangevalues" id="loanAmountInput" name="loan_amount" min="{{ config('settings.loan.min_amount', 1000) }}" max="{{ config('settings.loan.max_amount', 100000) }}" value="{{ config('settings.loan.min_amount', 1000) }}" aria-describedby="loanAmountHelp" required>
                                </div>
                                <div id="loanAmountHelp" class="form-text">Enter an amount between ${{ config('settings.loan.min_amount', 1000) }} and ${{ config('settings.loan.max_amount', 100000) }}.</div>
                                <input type="range" class="form-range" id="loanAmountRange" min="{{ config('settings.loan.min_amount', 1000) }}" max="{{ config('settings.loan.max_amount', 100000) }}" value="{{ config('settings.loan.min_amount', 1000) }}" aria-label="Loan amount range">
                            </div>

                            <!-- Tenure -->
                            <div class="mb-3">
                                <label class="form-label" for="tenureInput">Loan Tenure (Months)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Months</span>
                                    <input type="number" class="form-control rangevalues" id="tenureInput" name="tenure_months" min="1" max="{{ config('settings.loan.repayment_period', 60) }}" value="1" aria-describedby="tenureHelp" required>
                                </div>
                                <div id="tenureHelp" class="form-text">Select a tenure between 1 and {{ config('settings.loan.repayment_period', 60) }} months.</div>
                                <input type="range" class="form-range" id="tenureRange" min="1" max="{{ config('settings.loan.repayment_period', 60) }}" value="1" aria-label="Loan tenure range">
                            </div>

                            <!-- Interest Rate -->
                            <div class="mb-3">
                                <label class="form-label" for="interestRateInput">Annual Interest Rate (%)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">%</span>
                                    <input type="number" class="form-control rangevalues" id="interestRateInput" name="interest_rate" step="0.1" value="{{ config('settings.loan.interest_rate', 5) }}" aria-describedby="interestRateHelp" required>
                                </div>
                                <div id="interestRateHelp" class="form-text">Enter an interest rate between 0% and 50%.</div>
                                <input type="range" class="form-range" id="interestRateRange" min="0" max="50" step="0.1" value="{{ config('settings.loan.interest_rate', 5) }}" aria-label="Interest rate range">
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="text-center mb-3 position-relative" style="min-height: 200px;">
                                <canvas id="emiChart" style="max-height: 200px;" aria-label="Pie chart showing principal and interest breakdown"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h4 id="totalPayment" class="mb-0 fw-bold">N/A</h4>
                                    <p class="text-muted small">Total Repayment</p>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h6>Your EMI will be</h6>
                                <h1 class="text-theme-1" id="emiResult">N/A <small class="fs-6 fw-normal">/month</small></h1>
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="text-secondary small mb-1">
                                        <span class="d-inline-block bg-theme-1 rounded-circle me-1" style="width:10px; height:10px;"></span>Principal
                                    </p>
                                    <h5 id="principalAmount">N/A</h5>
                                </div>

                                <div class="col-6">
                                    <p class="text-secondary small mb-1">
                                        <span class="d-inline-block bg-theme-1-subtle rounded-circle me-1" style="width:10px; height:10px;"></span>Interest
                                    </p>
                                    <h5 id="totalInterest">N/A</h5>
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
                        <div class="col-12 col-md-12 mb-3">
                            <div class="form-floating mb-3">
                                <input id="title" type="text" class="form-control rounded-4" name="title" autofocus>
                                <div class="invalid-feedback">Loan title is required.</div>
                                <label for="title">Loan Title</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <textarea id="loan_reason" rows="4" name="loan_reason" class="form-control rounded-4" placeholder="Loan Reason"></textarea>
                            <div class="invalid-feedback">Loan reason is required.</div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <textarea id="loan_collateral" rows="4" name="loan_collateral" class="form-control rounded-4" placeholder="Collateral Information"></textarea>
                            <div class="invalid-feedback">Collateral info is required.</div>
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" name="monthly_emi" id="monthlyEmiInput">
                    <input type="hidden" name="total_interest" id="totalInterestInput">
                    <input type="hidden" name="total_payment" id="totalPaymentInput">
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="confirmCheck" checked>
                <label class="form-check-label" for="confirmCheck">
                    I confirm that all provided information is accurate.
                </label>
                <div class="invalid-feedback">You must confirm the accuracy of the provided information.</div>
            </div>

            <div class="row mb-4">
                <div class="col-auto">
                    <button type="submit" id="requestLoan" class="btn btn-theme">Submit Loan Request</button>
                </div>
            </div>
        </form>
    </div>
@endsection
