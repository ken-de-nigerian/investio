@extends('layouts.app')
@section('content')
    <!-- Content  -->
    <div class="container mt-4" id="main-content">
        <!-- Welcome box -->
        <div class="row align-items-center">
            <div class="col-12 col-lg mb-4">
                @php
                    $hour = now()->hour;
                    $greeting = match (true) {
                        $hour >= 0 && $hour < 6 => 'Good Morning',
                        $hour >= 6 && $hour < 12 => 'Good Morning',
                        $hour >= 12 && $hour < 17 => 'Good Afternoon',
                        $hour >= 17 && $hour < 22 => 'Good Evening',
                        default => 'Good Night',
                    };
                @endphp

                <h3 class="fw-normal mb-0 text-secondary">{{ $greeting }},</h3>
                <h1>{{ $auth['user']->first_name }} {{ $auth['user']->last_name }}</h1>
                
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <i class="bi bi-geo-alt-fill me-1 text-muted fs-5 align-middle"></i>
                        {{ ucfirst(str_replace('_', ' ', $auth['user']->profile->country ?? 'N/A')) }}
                    </div>

                    <span class="badge badge-sm badge-light text-bg-{{ $auth['user']->kyc && $auth['user']->kyc->status == 'approved' ? 'success' : ($auth['user']->kyc && $auth['user']->kyc->status == 'pending' ? 'warning' : 'danger') }}">
                        <i class="bi bi-circle-fill me-1"></i>
                        {{ ucfirst($auth['user']->kyc->status ?? 'unverified') }}
                    </span>
                </div>

                <div class="row gx-3 align-items-center mt-3">
                    <div class="col-12 col-md-11 col-xxl-9 mb-4">
                        <div class="input-group">
                            <input id="referralURL" type="text" class="form-control form-control-lg border-theme-1" placeholder="Your Referral Link" aria-describedby="button-addon2" value="{{ route('register', ['ref' => auth()->user()->profile->account_number ?? '']) }}" readonly>
                            <button class="btn btn-lg btn-outline-theme" type="button" id="button-addon2" onclick="copyToClipboard(document.getElementById('referralURL'))"><i class="bi bi-copy"></i></button>
                        </div>
                    </div>

                    <div class="col-12"></div>

                    <div class="col-auto">
                        <div class="row gx-3">
                            <div class="col-auto theme-green mb-3">
                                <span class="avatar avatar-40 rounded border-theme-1 border text-theme-1">
                                    <i class="bi bi-person-check h5"></i>
                                </span>
                            </div>
                            <div class="col-auto theme-green mb-3">
                                <p class="text-theme-1 small">
                                    Invite your friends to join us and<br>
                                    earn a {{ config('settings.referral.commission') }}% bonus from their first investment.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total profit -->
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <p class="text-secondary small mb-2">Total Profit</p>
                        <h4 class="mb-3">${{ number_format($total_profit, 2) }}k</h4>
                        @php
                            $profit_percentage = $total_invested > 0 ? ($total_profit / $total_invested) * 100 : 0;
                        @endphp

                        @if($profit_percentage > 0)
                            <span class="badge badge-light text-bg-success">
                                <i class="me-1 bi bi-arrow-up-short"></i>{{ number_format($profit_percentage, 2) }}%
                            </span>
                        @else
                            <span class="badge badge-light text-bg-secondary">
                                <i class="me-1 bi bi-dash"></i>0.00%
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Current Portfolio Value -->
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <p class="text-secondary small mb-2">Portfolio Value</p>
                        <h4 class="mb-3">${{ number_format($current_value, 2) }}k</h4>
                        @php
                            $value_growth = $total_invested > 0 ? (($current_value - $total_invested) / $total_invested) * 100 : 0;
                        @endphp
                        @if($value_growth > 0)
                            <span class="badge badge-light text-bg-success">
                                <i class="me-1 bi bi-arrow-up-short"></i>{{ number_format($value_growth, 2) }}%
                            </span>
                        @elseif($value_growth < 0)
                            <span class="badge badge-light text-bg-danger">
                                <i class="me-1 bi bi-arrow-down-short"></i>{{ number_format(abs($value_growth), 2) }}%
                            </span>
                        @else
                            <span class="badge badge-light text-bg-secondary">
                                <i class="me-1 bi bi-dash"></i>0.00%
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Total Invested -->
            <div class="col-12 col-sm-4 col-lg-3 col-xl-2 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <p class="text-secondary small mb-2">Total Invested</p>
                        <h4 class="mb-3">${{ number_format($total_invested, 2) }}k</h4>
                        <span class="badge badge-light text-bg-info">
                            <i class="me-1 bi bi-wallet2"></i>Principal
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- summary quick -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card adminuiux-card rounded-4 border position-relative overflow-hidden h-100">
                    <div class="card-body z-index-1">
                        <div class="row align-items-center justify-content-center h-100 py-4">
                            <div class="col-11">
                                <h2 class="fw-normal">Your portfolio value has grown by</h2>
                                <h1 class="mb-3">${{ number_format($total_profit, 2) }}k</h1>
                                <p>Total profit from completed investments</p>
                            </div>
                        </div>
                    </div>

                    <div class="position-absolute top-0 end-0 opacity-25">
                        <i class="bi bi-stars display-4"></i>
                    </div>
                </div>
            </div>

            <!-- Summary chart -->
            <div class="col-12 col-lg-6 col-xl-8 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="row gx-0">
                        <!-- summary account -->
                        <div class="col-12 col-xl-4">
                            <div class="card-header">
                                <h6>Summary</h6>
                            </div>

                            <div class="card-body pb-0">
                                <div class="card adminuiux-card rounded-4 border bg-theme-1 mb-3">
                                    <div class="card-body">
                                        <p class="text-white mb-2">Current Value</p>
                                        <h4 class="fw-medium text-white">$ {{ number_format($current_value, 2) }}k</h4>
                                    </div>
                                </div>

                                <div class="card adminuiux-card rounded-4 border bg-theme-1-subtle mb-3">
                                    <div class="card-body">
                                        <p class="text-secondary mb-2">Profit Revenue</p>
                                        <h4 class="fw-medium">$ {{ number_format($total_profit, 2) }}k</h4>
                                    </div>
                                </div>

                                <div class="card adminuiux-card rounded-4 border bg-theme-1-subtle mb-3">
                                    <div class="card-body">
                                        <p class="text-secondary mb-2">Total Investment</p>
                                        <h4 class="fw-medium">$ {{ number_format($total_invested, 2) }}k</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- chart section -->
                        <div class="col-12 col-xl-8">
                            <div class="card-body px-1">
                                <div class="w-100 height-300">
                                    <canvas id="investmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- chart -->
            <div class="col-12 col-md-12 col-xl-8 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Cash Flow</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="height-250 mb-3">
                            <canvas id="areachartblue1"></canvas>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card adminuiux-card bg-theme-1 border rounded-4">
                                    <div class="card-body z-index-1">
                                        <h4 class="fw-medium text">${{ number_format($income, 2) }}</h4>
                                        <p class="opacity-75">Income <span class="fs-14"><i
                                                    class="bi bi-arrow-{{ $incomeDirection }}"></i> {{ $incomeChange }}%</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card adminuiux-card bg-theme-1-subtle border rounded-4">
                                    <div class="card-body z-index-1">
                                        <h4 class="fw-medium">${{ number_format($expense, 2) }}</h4>
                                        <p class="text-secondary">Expense <span class="text-success fs-14"><i
                                                    class="bi bi-arrow-{{ $expenseDirection }}"></i> {{ $expenseChange }}%</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- wallet balance -->
            <div class="col-12 col-md-12 col-xl-4 mb-4">
                <div class="card adminuiux-card overflow-hidden border rounded-4 height-dynamic">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-30 rounded-circle bg-theme-1-subtle text-theme-1">
                                    <i class="bi bi-wallet"></i>
                                </span>
                            </div>

                            <div class="col px-0">
                                <h6 class="mb-0">My Wallet</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center bg-theme-1-subtle">
                        <div class="position-relative mx-auto" style="width: 160px; height: 160px; @if(!$top_performing_investment) margin-bottom: 100px; @endif">
                            <svg class="rotating-ring position-absolute top-0 start-0" width="160" height="160">
                                <circle cx="80" cy="80" r="70" stroke="#4caf50" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="220" stroke-dashoffset="60"/>
                            </svg>

                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <div style="line-height: 1;">
                                    <h4 class="mb-1" style="font-size: 1.2rem;">
                                        ${{ number_format($auth['user']->balance ?? 0, 2) }}
                                    </h4>
                                    <small class="opacity-75">Your Balance</small>
                                </div>
                            </div>
                        </div>

                        @if($top_performing_investment && $top_performing_investment->count())
                            <p class="text-secondary small mt-4 mb-2">
                                Top performing investment: <b class="text-theme-1">{{ $top_performing_investment->plan->name }}</b>
                            </p>

                            <h5 class="fw-medium mb-3">
                                ${{ number_format($top_performing_investment->amount) }}
                                <span class="text-success fs-14 fw-normal">
                                <i class="bi bi-caret-up-fill me-1 fs-14"></i>{{ $top_performing_investment->cagr }}%
                            </span>
                            </h5>
                        @endif

                        <!-- Action Buttons -->
                        <div class="row justify-content-center g-2 mt-2">
                            <div class="col-auto py-2">
                                <a class="btn btn-lg btn-square btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#sendmoneymodal" title="Send Money">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>

                            <div class="col-auto py-2">
                                <a class="btn btn-lg btn-square btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#addmoneymodal" title="Add Money">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                            </div>

                            <div class="col-auto py-2">
                                <a href="{{ route('user.domestic.transfer') }}" class="btn btn-lg btn-square btn-outline-secondary rounded-pill" title="Domestic Transfer">
                                    <i class="bi bi-calendar-event"></i>
                                </a>
                            </div>

                            <div class="col-auto py-2">
                                <a href="{{ route('user.wire.transfer') }}" class="btn btn-lg btn-square btn-outline-secondary rounded-pill" title="Wire Transfer">
                                    <i class="bi bi-globe-americas"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- updates -->
            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0">Updates:</h6>
                        <p class="small text-secondary">Today <span class="text-danger">Live</span></p>
                    </div>

                    <div class="col-12 col-sm-10">
                        <!-- TradingView Widget BEGIN -->
                        @include('partials.trading-view-ticker')
                        <!-- TradingView Widget END -->
                    </div>
                </div>
            </div>

            <!-- investment category doughnut chart -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="card-body">
                                <div
                                    class="position-relative d-flex align-items-center justify-content-center text-center mb-3">
                                    @if($total_invested)
                                        <div class="position-absolute">
                                            <h4 class="mb-0">$ {{ number_format($total_invested, 2) }}k</h4>
                                            <p class="text-secondary small">Portfolio Value</p>
                                        </div>
                                    @endif
                                    <canvas id="doughnutchart" class="mx-auto width-240 height-240"></canvas>
                                </div>

                                @if($total_invested)
                                    <p class="text-secondary text-center small">
                                        You have invested in different types of categories shown as above and summary of
                                        each category.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- investment categories -->
            <div class="col-12 col-md-8 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="card-header">
                                <h6 class="my-1">Investment Categories</h6>
                            </div>

                            <div class="card-body">
                                <div class="row mb-3">
                                    @foreach($plan_categories as $category)
                                        <div class="col-6 col-lg-3 mb-4">
                                            <p class="text-secondary small mb-2">
                                                <span class="me-1 avatar avatar-10 rounded"
                                                      style="background-color: {{ $category->color ?? '#ccc' }};"></span>
                                                {{ $category->name }}
                                            </p>
                                            <h4 class="ps-3 fw-medium">
                                                $ {{ number_format($category->total_invested ?? 0, 2) }}k<br/>
                                                <span class="text-success fs-14 fw-normal">
                                                    <i class="bi bi-caret-up-fill me-1 fs-14"></i> {{ number_format($category->percentage ?? 0, 2) }}%
                                                </span>
                                            </h4>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- start investment -->
            <div class="col-12">
                <div class="row mb-2">
                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a data-bs-toggle="modal" data-bs-target="#sendmoneymodal" title="Send Money"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 h3 bi bi-bank mb-3"></i>
                                <p class="text-secondary small">Same Bank Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.domestic.transfer') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-calendar-event h3 mb-3"></i>
                                <p class="text-secondary small">Domestic Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.wire.transfer') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-globe-americas h3 mb-3"></i>
                                <p class="text-secondary small">Wire Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.loan') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-cash-stack h3 mb-3"></i>
                                <p class="text-secondary small">Loan Financing</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.profile', ['tab' => 'cards']) }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-credit-card-2-front-fill h3 mb-3"></i>
                                <p class="text-secondary small">Virtual Cards</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.goal') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-tags h3 mb-3"></i>
                                <p class="text-secondary small">My Goals</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.investment') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-piggy-bank h3 mb-3"></i>
                                <p class="text-secondary small">Investments</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.statistics') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-bar-chart h3 mb-3"></i>
                                <p class="text-secondary small">Statistics</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- goals -->
            <div class="col-12 col-xxl-12">
                <div class="row">
                    @foreach($goals as $goal)
                        <!-- Goal -->
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card adminuiux-card rounded-4 border">
                                <div class="card-body">
                                    <div class="row gx-3 mb-3">
                                        <div class="col-auto">
                                            <i class="bi bi-{{ $goal->category->icon }} fs-4 avatar avatar-50 text-white rounded" style="background-color: {{ $goal->category->color }};"></i>
                                        </div>

                                        <div class="col">
                                            <h4 class="mb-0">${{ number_format($goal->target_amount, 2) }}</h4>
                                            <p class="small opacity-75">Goal: {{ $goal->title }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-auto">{{ round($goal->days_remaining) }} days left</div>
                                        <div class="col"></div>
                                        <div class="col-auto">${{ number_format($goal->current_amount, 2) }} saved</div>
                                    </div>

                                    <div class="progress height-10 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-striped bg-success" style="width: {{ $goal->progress_percentage }}%;"></div>
                                    </div>

                                    <div class="row small text-secondary">
                                        <div class="col-auto">{{ $goal->progress_percentage }}%</div>
                                        <div class="col"></div>
                                        <div class="col-auto">90%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- investments history -->
            <div class="col-12">
                <div class="card adminuiux-card rounded-4 border mb-4">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>{{ ucfirst($sort ?? 'All') }} Investments</h6>
                            </div>

                            <div class="col-auto">
                                <select class="form-select form-select-sm rounded-pill border" id="sortSelect">
                                    <option value="" {{ $sort == '' ? 'selected' : '' }}>Sort By</option>
                                    <option value="completed" {{ $sort == 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="running" {{ $sort == 'running' ? 'selected' : '' }}>Running</option>
                                    <option value="liquidated" {{ $sort == 'liquidated' ? 'selected' : '' }}>
                                        Liquidated
                                    </option>
                                    <option value="cancelled" {{ $sort == 'cancelled' ? 'selected' : '' }}>Cancelled
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover investments-table">
                                <thead>
                                <tr>
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Interest</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($investments as $investment)
                                    <tr data-investment-id="{{ $investment->id }}"
                                        data-investment-amount="{{ $investment->amount }}"
                                        data-investment-profit="{{ $investment->expected_profit }}"
                                        data-remaining-time="{{ $investment->remaining_time }}"
                                        data-end-date="{{ $investment->end_date }}">
                                        <td data-label="Plan Name">
                                            <p class="mb-0">{{ $investment->plan->name }}</p>
                                            <p class="small text-theme-1">
                                                Started {{ $investment->start_date->format('j M Y') }}
                                            </p>
                                        </td>

                                        <td data-label="Category">
                                            <span class="badge badge-sm badge-light text-bg-secondary small"
                                                  style="font-size: 12px;">
                                                <i class="bi bi-{{ $investment->plan->category->icon }}"></i>
                                                {{ $investment->plan->category->name }}
                                            </span>
                                        </td>

                                        <td data-label="Amount">
                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($investment->amount, 2) }}</small>
                                        </td>

                                        <td data-label="Expected Profit">
                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($investment->expected_profit, 2) }}</small>
                                        </td>

                                        <td data-label="Interest Rate">
                                            <small class="fw-normal small" style="font-size: 12px;">{{ $investment->plan->interest_rate }}%</small>
                                            <span
                                                class="badge badge-sm badge-light text-bg-success mx-1 fw-normal small"
                                                style="font-size: 12px;">
                                                    Paid {{ $investment->plan->returns_period }}
                                            </span>
                                        </td>

                                        <td data-label="Total">
                                            @php
                                                $profit = $investment->amount + $investment->expected_profit;
                                            @endphp
                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($profit, 2) }}</small>

                                            @if($investment->completion_percentage != 100)
                                                <div class="text-secondary small" id="countdown"
                                                     data-end-date="{{ $investment->end_date }}" style="font-size: 12px;"></div>
                                            @endif
                                        </td>

                                        <td data-label="Progress">
                                            <div class="progress" style="height: 6px; width: 100px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: {{ $investment->completion_percentage }}%"
                                                     aria-valuenow="{{ $investment->completion_percentage }}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>

                                            <p class="text-secondary small margin-top"
                                               id="completion-percentage-{{ $investment->id }}">
                                                {{ $investment->completion_percentage }}% Complete
                                            </p>
                                        </td>

                                        <td data-label="Duration">
                                            <span class="badge badge-sm badge-light text-bg-secondary small"
                                                  style="font-size: 12px;">
                                                Matures in {{ $investment->plan->duration_display }}
                                            </span>
                                        </td>

                                        <td data-label="Action">
                                            @if($investment->status == 'running' || $investment->completion_percentage != 100)
                                                <button onclick="liquidateInvestment({{ $investment->id }})"
                                                        class="btn btn-sm btn-outline-secondary rounded-4 small"
                                                        style="font-size: 12px;">
                                                    Liquidate $ {{ number_format($profit, 2) }}
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary rounded-4 small"
                                                        style="font-size: 12px;">
                                                    {{ ucfirst($investment->status) }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No investments found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $investments->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .height-dynamic {
            height: 445px !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Set data globally before loading the script
        window.dashboardData = {
            categories: @json($plan_categories->pluck('name')),
            amounts: @json($plan_categories->pluck('total_invested')),
            colors: @json($plan_categories->pluck('color')),
            categoryData: @json($category_data)
        };

        function copyToClipboard(inputField) {
            // Select the text inside the input field
            inputField.select();
            inputField.setSelectionRange(0, 99999); /* For mobile devices */

            const showError = (message) => {
                iziToast.error({ ...iziToastSettings, message });
            };

            const showSuccess = (message) => {
                iziToast.success({ ...iziToastSettings, message });
            };

            // Use Clipboard API to copy text
            navigator.clipboard.writeText(inputField.value)
                .then(() => {
                    // If copying was successful
                    showSuccess("Copied: " + inputField.value);
                })
                .catch(() => {
                    // If copying failed
                    showError("Failed to copy. Please try again.");
                });
        }
    </script>
@endpush
