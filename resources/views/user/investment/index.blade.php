@extends('layouts.app')
@section('content')
    <!-- Content  -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-6 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Investments</li>
                    </ol>
                </nav>
                <h5>Investments</h5>
            </div>

            <div class="col-6 col-sm text-end">
                <a href="{{ route('user.investment.list') }}" class="btn btn-theme">
                    <i class="bi bi-plus-lg"></i> My <span class="d-none d-md-inline">Portfolio</span>
                </a>
            </div>
        </div>

        <!-- Welcome & Investment Header -->
        <div class="row align-items-center py-4">
            <div class="col-12 col-lg-6 col-xxl-8">
                <h3 class="fw-normal mb-0 text-secondary">Investment Plans</h3>
                <h1 class="mb-4">To Grow Your Wealth</h1>

                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-12 col-md-11 col-xxl-8 mb-4">
                        <form method="GET" action="{{ route('user.investment.plans') }}" id="search-form">
                            <div class="input-group">
                                <input class="form-control border-end-0" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by plan name, returns, or category..." id="search-input">
                                <button class="btn btn-lg btn-theme btn-square" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Benefits Highlights -->
                    <div class="col-12"></div>

                    <!-- Professional Support -->
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto theme-green mb-4">
                                <span class="avatar avatar-40 rounded border-theme-1 border text-theme-1">
                                    <i class="bi bi-people-fill h5"></i>
                                </span>
                            </div>
                            <div class="col-auto theme-green mb-4">
                                <p class="text-theme-1 small">200+ Financial Experts<br>Ready to Assist You</p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Assurance -->
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto theme-purple mb-4">
                                <span class="avatar avatar-40 rounded border-theme-1 border text-theme-1">
                                    <i class="bi bi-shield-lock h5"></i>
                                </span>
                            </div>
                            <div class="col-auto theme-purple mb-4">
                                <p class="text-theme-1 small">Secured & Regulated<br>Investment Platform</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Offer Card -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card adminuiux-card rounded-4 border position-relative overflow-hidden">
                    <div class="card-body z-index-1">
                        <h2 class="text-white">Limited Time Offer!</h2>
                        <h4 class="fw-medium">Get <b>1% Extra Returns</b> on All<br>New Investments This Month</h4>
                        <p class="mb-4">*Terms and conditions apply</p>
                        <a href="{{ route('user.investment.plans') }}" class="btn btn-light rounded-4 my-1">Explore Plans</a>
                    </div>

                    <div class="position-absolute top-0 end-0 opacity-25">
                        <i class="bi bi-stars display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- investment categories -->
            <div class="col-12 mb-4">
                <h5>Investment Categories</h5>
            </div>
            <div class="col-12 mb-4">
                <div class="row mb-2">
                    @foreach($plan_categories as $category)
                        <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                            <a href="{{ route('user.investment.categories', $category->slug) }}" class="card adminuiux-card style-none text-center h-100 rounded-4 border">
                                <div class="card-body">
                                    <i class="avatar avatar-40 text-theme-1 h3 bi bi-{{ $category->icon }} mb-3"></i>
                                    <p class="text-secondary small">{{ $category->name }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- stock heatmap -->
            <div class="col-12 mb-4">
                <h5>Stock Market Heatmap</h5>
            </div>
            <div class="col-12 mb-4">
                <!-- TradingView Widget BEGIN -->
                @include('partials.trading-view')
                <!-- TradingView Widget END -->
            </div>

            @if($investments->count())
                <div class="col-12 mb-4">
                    <h5>Running Investments</h5>
                </div>
                <!-- running investments -->
                @foreach($investments as $investment)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card adminuiux-card mb-4 rounded-4 border"
                             data-investment-id="{{ $investment->id }}"
                             data-investment-amount="{{ $investment->amount }}"
                             data-investment-profit="{{ $investment->expected_profit }}"
                             data-remaining-time="{{ $investment->remaining_time }}"
                             data-end-date="{{ $investment->end_date }}">
                            <div class="card-body">
                                <div class="row align-items-center mb-4">
                                    <div class="col-6">
                                        <h5 class="fw-medium mb-1">{{ $investment->plan->name }}</h5>
                                        <p class="text-secondary mb-0" style="font-size: 14px !important;">
                                            <i class="bi bi-{{ $investment->plan->category->icon }}"></i>
                                            {{ $investment->plan->category->name }}
                                        </p>
                                    </div>

                                    <div class="col-6 text-end">
                                        <small>
                                            <span class="badge badge-sm badge-light text-bg-success fw-normal" style="font-size: 12px;">Paid {{ $investment->plan->returns_period }}</span>
                                        </small>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-6 text-start mb-3">
                                        <h6 class="fw-medium">${{ number_format($investment->amount, 2) }}</h6>
                                        <p class="text-secondary small">Started <span>{{ $investment->start_date->format('j M Y') }}</span></p>
                                    </div>

                                    <div class="col-6 text-end mb-3">
                                        <h6 class="fw-medium">${{ number_format($investment->expected_profit, 2) }} <small class="fw-light">({{ $investment->plan->interest_rate }}%)</small></h6>
                                        <p class="text-secondary small">Matures in <span>{{ $investment->plan->duration_display }}</span></p>
                                    </div>

                                    <!-- Completion and Countdown -->
                                    <div class="col-6 text-start">
                                        <h6 class="fw-medium text-success">+{{ $investment->cagr }}% <small class="fw-light">(CAGR)</small></h6>
                                        <p class="text-secondary small">
                                            <span id="completion-percentage-{{ $investment->id }}">{{ $investment->completion_percentage }}% <small class="fw-light">Complete</small></span>
                                        </p>
                                    </div>

                                    <div class="col-6 text-end">
                                        @php
                                            $profit = $investment->amount + $investment->expected_profit;
                                        @endphp
                                        <h6 class="fw-medium">${{ number_format($profit, 2) }} <small class="fw-light">total</small></h6>
                                        <div class="text-secondary small" id="countdown" data-end-date="{{ $investment->end_date }}"></div>
                                    </div>
                                </div>

                                <!-- Progress bar -->
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $investment->completion_percentage }}%" aria-valuenow="{{ $investment->completion_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <button onclick="liquidateInvestment({{ $investment->id }})" class="btn btn-outline-secondary rounded-4">Liquidate ${{ number_format($profit, 2) }} <small class="fw-light">profit</small></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 text-center mb-4">
                    <a href="{{ route('user.investment.list') }}" class="btn btn-outline-theme rounded-4 border">View Portfolio</a>
                </div>
            @endif

            <!-- investment plans -->
            <div class="col-12 mb-4">
                <h5>Investment Plans</h5>
            </div>
            <div class="col-12">
                @include('partials.investment-items', ['plans' => $plans])
            </div>
            <div class="col-12 text-center mb-4">
                <a href="{{ route('user.investment.plans') }}" class="btn btn-outline-theme rounded-4 border">View all investment plans</a>
            </div>
        </div>
    </div>
@endsection
