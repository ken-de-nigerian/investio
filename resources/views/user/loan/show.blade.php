@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <!-- summary loan account-->
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i
                                    class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.loan') }}">My Loans</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">@truncate($loan->title, 25)</li>
                    </ol>
                </nav>
                <h5>{{ $loan->title }}</h5>
            </div>
        </div>

        <div class="row">
            @php
                $repaymentPeriod = $loan->tenure_months;
                $paidEmi = $loan->paid_emi ?? 0;
                $progress = round(($paidEmi / $repaymentPeriod) * 100);
                $circleCircumference = 2 * pi() * 45;
                $dashOffset = $circleCircumference * (1 - $progress / 100);

                // Centralized date calculation logic
                $baseDate = $loan->created_at;
                $nextEmiNumber = $paidEmi + 1;
                $nextDueDate = null;
                $isLoanCompleted = $paidEmi >= $repaymentPeriod;

                if (!$isLoanCompleted) {
                    $nextDueDate = $baseDate->copy()->addMonths($nextEmiNumber);
                }
            @endphp

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card adminuiux-card mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="fw-medium text-success">$ {{ number_format($loan->loan_amount, 2) }}</h4>
                                <p><span class="text-secondary">Status: {{ ucfirst($loan->status) }}</span></p>
                            </div>

                            <div class="col-auto">
                                <div class="avatar avatar-60 position-relative mx-auto text-center">
                                    <div id="circleprogressgreen1">
                                        <svg viewBox="0 0 100 100" style="display: block; width: 100%;">
                                            <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                                  stroke="#eaf4d8" stroke-width="10" fill-opacity="0"></path>
                                            <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                                  stroke="rgb(145,195,0)" stroke-width="10" fill-opacity="0"
                                                  style="stroke-dasharray: {{ $circleCircumference }}, {{ $circleCircumference }}; stroke-dashoffset: {{ $dashOffset }};"></path>
                                        </svg>

                                        <div class="progressbar-text"
                                             style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); color: rgb(145, 195, 0); font-weight: bold;">
                                            {{ $progress }}<small>%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- interest rate -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card adminuiux-card theme-teal mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($loan->total_interest, 2) }}</h4>
                        <p><span class="text-secondary">Interest Rate</span></p>
                    </div>
                </div>
            </div>

            <!-- emi -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card adminuiux-card theme-orange mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($loan->monthly_emi, 2) }}</h4>
                        <p><span class="text-secondary">{{ $loan->paid_emi }}<span
                                    class="opacity-75">/{{ $loan->tenure_months }}</span>, Monthly EMI</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- next due -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card adminuiux-card mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <div class="row align-items-center">
                            <div class="col">
                                @if($isLoanCompleted)
                                    <h4 class="fw-medium text-success">
                                        Loan Completed
                                    </h4>
                                    <p><span class="text-secondary">All EMIs Paid</span></p>
                                @else
                                    @php
                                        $isDuePassed = now()->gt($nextDueDate);
                                        $shouldStrike = $isDuePassed;
                                    @endphp
                                    <h4 class="fw-medium text-theme-1 theme-orange {{ $shouldStrike ? 'text-decoration-line-through' : '' }}">
                                        {{ $nextDueDate->format('j F Y') }}
                                    </h4>
                                    <p><span class="text-secondary">Next EMI Due Date</span></p>
                                @endif
                            </div>

                            <div class="col-auto">
                                @if(!$isLoanCompleted)
                                    <button class="btn btn-sm btn-outline-theme rounded-pill"
                                            title="Pay Now" data-bs-toggle="modal" data-bs-target="#repayLoanmodal">
                                        <i class="bi bi-arrow-up-right me-1"></i> Pay
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMI transaction -->
            <div class="col-12 col-md-12 col-xl-12 col-xxl-8 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>EMI Transaction</h6>
                            </div>
                        </div>
                    </div>
                    <!-- EMI transaction list -->
                    <ul class="list-group list-group-flush border-top bg-none">
                        @php
                            $monthlyEmi = $loan->monthly_emi ?? 0;
                        @endphp
                        @for($i = 1; $i <= $repaymentPeriod; $i++)
                            @php
                                // Calculate due date: EMI #1 is due 1 month after loan creation
                                $dueDate = $baseDate->copy()->addMonths($i);

                                if ($i <= $paidEmi) {
                                    $status = 'Paid';
                                    $badge = 'success';
                                    $shouldStrike = true; // Always strike paid EMIs
                                } elseif ($i == $paidEmi + 1) {
                                    $status = 'Upcoming';
                                    $badge = 'warning';
                                    $shouldStrike = false; // Don't strike upcoming EMI
                                } else {
                                    $status = 'Pending';
                                    $badge = 'secondary';
                                    // Strike if due date has passed
                                    $shouldStrike = now()->gt($dueDate);
                                }
                            @endphp
                            <li class="list-group-item">
                                <div class="row gx-3 align-items-center">
                                    <div class="col">
                                        <p class="mb-1 fw-medium">EMI #{{ $i }}</p>
                                        <p class="text-secondary small {{ $shouldStrike ? 'text-decoration-line-through' : '' }}">
                                            Due date: {{ $dueDate->format('jS F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h6 class="text-{{ $badge == 'success' ? 'success' : 'dark' }}">${{ number_format($monthlyEmi, 2) }}</h6>
                                        <div class="badge badge-sm badge-light text-bg-{{ $badge }}">{{ $status }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="avatar avatar-40 rounded-circle border border-theme-1 bg-theme-1-subtle text-theme-1"
                                                @if($status === 'Upcoming') title="Pay Now" data-bs-toggle="modal" data-bs-target="#repayLoanmodal" @else disabled @endif>
                                            <i class="bi bi-arrow-up-right h5"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>

            <!-- loan overview -->
            <div class="col-12 col-lg-12 col-xl-6 col-xxl-4 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <h6>Overview</h6>
                    </div>

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h6 class="mb-1">{{ $loan->created_at->format('j F Y') }}</h6>
                                <p class="small opacity-75">Loan Start Date</p>
                            </div>

                            <div class="col-12 mb-4">
                                <h6 class="mb-1">{{ $loan->loan_end_date->format('j F Y') }}</h6>
                                <p class="small opacity-75">Loan End Date</p>
                            </div>

                            <div class="col-12 mb-4">
                                <h6 class="mb-1">{{ $loan->paid_emi }}<span
                                        class="opacity-50">/{{ $loan->tenure_months }}</span></h6>
                                <p class="small opacity-75">Paid EMI</p>
                            </div>

                            <div class="col-6 mb-4">
                                <h6 class="mb-1">Repayment</h6>
                                <p class="small opacity-75">Total Interest</p>
                            </div>

                            <div class="col-6 text-end mb-4">
                                <h6 class="mb-1">${{ number_format($loan->total_payment, 2) }}</h6>
                                <p class="small opacity-75">${{ number_format($loan->total_interest, 2) }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
