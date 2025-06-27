@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-6 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">My Loans</li>
                    </ol>
                </nav>
                <h5>My Loans</h5>
            </div>

            <div class="col-6 col-sm text-end">
                <a href="{{ route('user.loan.create') }}" class="btn btn-theme">
                    <i class="bi bi-plus-lg"></i> Request <span class="d-none d-md-inline">Loan</span>
                </a>
            </div>
        </div>

        <!-- loan request form -->
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
            <strong>Need quick financial support?</strong> Submit a <a href="{{ route('user.loan.create') }}" class="alert-link">loan request</a> now to get
            started—it's fast, easy, and secure.
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

            <!-- EMI Calculator -->
            <div class="col-12 mb-4">
                <div class="row g-4">
                    <!-- Input Section Card -->
                    <div class="col-12 col-lg-6">
                        <div class="card adminuiux-card h-100 border rounded-4">
                            <div class="card-header">
                                <h6 class="mb-0">EMI Calculator</h6>
                            </div>
                            <div class="card-body">
                                <!-- Loan Amount -->
                                <div class="mb-3">
                                    <label class="form-label" for="loanAmountInput">Loan Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control rangevalues" id="loanAmountInput"
                                               name="loan_amount" min="{{ config('settings.loan.min_amount', 1000) }}"
                                               max="{{ config('settings.loan.max_amount', 100000) }}"
                                               value="{{ config('settings.loan.min_amount', 1000) }}"
                                               aria-describedby="loanAmountHelp" required>
                                    </div>

                                    <div id="loanAmountHelp" class="form-text">Enter an amount between
                                        ${{ config('settings.loan.min_amount', 1000) }} and
                                        ${{ config('settings.loan.max_amount', 100000) }}.
                                    </div>

                                    <input type="range" class="form-range" id="loanAmountRange"
                                           min="{{ config('settings.loan.min_amount', 1000) }}"
                                           max="{{ config('settings.loan.max_amount', 100000) }}"
                                           value="{{ config('settings.loan.min_amount', 1000) }}"
                                           aria-label="Loan amount range"
                                    >
                                </div>

                                <!-- Tenure -->
                                <div class="mb-3">
                                    <label class="form-label" for="tenureInput">Loan Tenure (Months)</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">Months</span>
                                        <input type="number" class="form-control rangevalues" id="tenureInput"
                                               name="tenure_months" min="1"
                                               max="{{ config('settings.loan.repayment_period', 60) }}" value="1"
                                               aria-describedby="tenureHelp" required
                                        >
                                    </div>

                                    <div id="tenureHelp" class="form-text">Select a tenure between 1 and
                                        {{ config('settings.loan.repayment_period', 60) }} months.
                                    </div>

                                    <input type="range" class="form-range" id="tenureRange" min="1"
                                           max="{{ config('settings.loan.repayment_period', 60) }}" value="1"
                                           aria-label="Loan tenure range"
                                    >
                                </div>

                                <!-- Interest Rate -->
                                <div class="mb-3">
                                    <label class="form-label" for="interestRateInput">Annual Interest Rate (%)</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">%</span>
                                        <input type="number" class="form-control rangevalues" id="interestRateInput"
                                               name="interest_rate" step="0.1"
                                               value="{{ config('settings.loan.interest_rate', 5) }}"
                                               aria-describedby="interestRateHelp" required
                                        >
                                    </div>

                                    <div id="interestRateHelp" class="form-text">Enter an interest rate between 0% and 50%.</div>
                                    <input type="range" class="form-range" id="interestRateRange" min="0" max="50" step="0.1"
                                           value="{{ config('settings.loan.interest_rate', 5) }}" aria-label="Interest rate range">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section Card -->
                    <div class="col-12 col-lg-6">
                        <div class="card adminuiux-card h-100 border rounded-4">
                            <div class="card-header">
                                <h6 class="mb-0">EMI Breakdown</h6>
                            </div>

                            <div class="card-body">
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
                                            <span class="d-inline-block bg-theme-1 rounded-circle me-1" style="width:10px; height:10px;"></span>
                                            Principal
                                        </p>
                                        <h5 id="principalAmount">N/A</h5>
                                    </div>

                                    <div class="col-6">
                                        <p class="text-secondary small mb-1">
                                            <span class="d-inline-block bg-theme-1-subtle rounded-circle me-1" style="width:10px; height:10px;"></span>
                                            Interest
                                        </p>
                                        <h5 id="totalInterest">N/A</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-12 col-xl-6 col-xxl-12">
                <div class="row">
                    @foreach($loans as $loan)
                        @php
                            // Define theme class based on loan status
                            $themes = [
                                'pending' => 'theme-yellow',
                                'approved' => 'adminuiux-card',
                                'rejected' => 'theme-red',
                                'disbursed' => 'theme-purple',
                                'completed' => 'theme-green',
                            ];
                            $theme = $themes[$loan->status] ?? 'theme-yellow';

                            // Dynamic label/title for the card
                            $statusTitles = [
                                'pending' => 'Processing...',
                                'approved' => $loan->title,
                                'rejected' => 'Rejected',
                                'disbursed' => 'Disbursed',
                                'completed' => $loan->title,
                            ];
                            $loan_title = $statusTitles[$loan->status] ?? 'Pending';

                            $link = route('user.loan.show', $loan->id);
                            $isApprovedOrCompleted = in_array($loan->status, ['approved', 'completed']);
                            $icon = $isApprovedOrCompleted ? 'bi-house' : 'bi-arrow-clockwise';
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4 col-xl-6 col-xxl-3">
                            <div class="card rounded-4 border-theme-1 {{ $theme }} mb-4">
                                <div class="card-header bg-theme-1-subtle border-bottom">
                                    <div class="row gx-3 align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 bg-theme-1-subtle text-theme-1 rounded">
                                                <i class="bi {{ $icon }} h5"></i>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <a href="{{ $link }}" class="style-none text-theme-1">
                                                <h5 class="mb-0">@truncate($loan->title, 25)</h5>
                                            </a>

                                            <p class="text-secondary small">
                                                Loan
                                                Account: {{ hidePhoneNumber($loan->user->profile->account_number) ?? 'xx-xxxx' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body z-index-1 {{ $loan->status === 'completed' ? 'bg-theme-1-subtle' : '' }}">
                                    <div class="row gx-3 align-items-center">
                                        <div class="col">
                                            <h4 class="fw-medium mb-0">
                                                $ {{ number_format($loan->loan_amount ?? 0, 2) }}</h4>
                                            @if($loan->status === 'completed')
                                                <p class="text-secondary small">
                                                    This loan is fully paid & completed
                                                    on {{ Carbon::parse($loan->completed_at)->format('jS F Y') }}
                                                </p>
                                            @elseif($loan->status === 'approved')
                                                <p class="text-secondary small">
                                                    Paid {{ $loan->paid_emi }} <span
                                                        class="opacity-75">/{{ $loan->tenure_months }}</span>
                                                    EMI,
                                                    Interest Rate {{ '$' . number_format($loan->total_interest, 2) }}
                                                </p>
                                            @else
                                                <p class="text-secondary small">
                                                    ${{ number_format($loan->monthly_emi ?? 140, 2) }} EMI,
                                                    Interest Rate {{ '$' . number_format($loan->total_interest, 2) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="col-auto">
                                            <a href="{{ $link }}" class="btn btn-square btn-link">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- EMI Transaction -->
            <div class="col-12 col-md-12 col-lg-12 mb-4">
                <div class="card adminuiux-card mb-4 rounded-4 border">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>EMI Transactions</h6>
                            </div>
                        </div>
                    </div>

                    <!-- EMI transaction list -->
                    <ul class="list-group list-group-flush border-top bg-none">
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

                            <li class="list-group-item">
                                <div class="row gx-3 align-items-center">
                                    <div class="col">
                                        <p class="mb-1 fw-medium">@truncate($loan->title, 25)</p>
                                        <p class="text-secondary small {{ $shouldStrike ? 'text-decoration-line-through' : '' }}">
                                            @if($isLoanCompleted)
                                                Loan completed on: {{ $nextDueDate->format('jS F Y') }}
                                            @else
                                                Next due date: {{ $nextDueDate->format('jS F Y') }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-auto text-end">
                                        <h6 class="text-{{ $badge === 'success' ? 'success' : 'dark' }}">
                                            ${{ number_format($loan->monthly_emi ?? 0, 2) }}
                                        </h6>
                                        @php
                                            // Use loan status for badge instead of calculated status
                                            $statusBadges = [
                                                'pending' => 'text-bg-warning',
                                                'approved' => $isLoanCompleted ? 'text-bg-success' : 'text-bg-warning',
                                                'rejected' => 'text-bg-danger',
                                                'disbursed' => 'text-bg-info',
                                                'completed' => 'text-bg-success',
                                            ];
                                            $badgeClass = $statusBadges[$loan->status] ?? 'text-bg-secondary';
                                            $displayStatus = $isLoanCompleted ? 'Completed' : ucfirst($loan->status);
                                        @endphp
                                        <div class="badge badge-sm badge-light {{ $badgeClass }}">{{ $displayStatus }}</div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('user.loan.show', $loan->id) }}"
                                           class="avatar avatar-40 rounded-circle text-decoration-none border border-theme-1 bg-theme-1-subtle text-theme-1">
                                            <i class="bi bi-arrow-up-right h5"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="row gx-3 align-items-center">
                                    <div class="text-center">
                                        <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                            <div class="empty-notification-elem">
                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                    <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid" alt="not-found-pic" loading="lazy" />
                                                </div>

                                                <div class="text-center pb-5 mt-2">
                                                    <h6 class="fs-18 fw-semibold lh-base">No active loans found.</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Pagination -->
                {{ $loans->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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
                triggerDebouncedUpdate();
            });

            range.addEventListener('input', () => {
                input.value = range.value;
                triggerDebouncedUpdate();
            });
        }

        syncInputAndRange(loanAmountInput, loanAmountRange);
        syncInputAndRange(tenureInput, tenureRange);
        syncInputAndRange(interestRateInput, interestRateRange);

        const triggerDebouncedUpdate = debounce(calculateAndRender, 300);

        function formatNumberAbbreviation(num) {
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

            emiResult.innerHTML = `$${formatNumberAbbreviation(emi)} <small class="fs-6 fw-normal">/month</small>`;
            totalInterestEl.textContent = `$${formatNumberAbbreviation(totalInterest)}`;
            totalPaymentEl.textContent = `$${formatNumberAbbreviation(totalPayment)}`;
            principalAmountEl.textContent = `$${formatNumberAbbreviation(principal)}`;

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

        window.addEventListener('DOMContentLoaded', calculateAndRender);
    </script>
@endpush
