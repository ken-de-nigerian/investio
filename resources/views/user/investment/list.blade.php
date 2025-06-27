@extends('layouts.app')
@section('content')
    <!-- Content  -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.investment') }}">Investments</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">My Portfolio</li>
                    </ol>
                </nav>
                <h5>My Portfolio</h5>
            </div>
        </div>

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

            <!-- investment categories -->
            <div class="col-12 mb-4">
                <h5>Investment Categories</h5>
            </div>
            <div class="col-12">
                <div class="row mb-2">
                    @foreach($plan_categories as $category)
                        <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                            <a href="{{ route('user.investment.categories', $category->slug) }}"
                               class="card adminuiux-card rounded-4 border style-none text-center h-100">
                                <div class="card-body">
                                    <i class="avatar avatar-40 text-theme-1 h3 bi bi-{{ $category->icon }} mb-3"></i>
                                    <p class="text-secondary small">{{ $category->name }}</p>
                                </div>
                            </a>
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

@push('scripts')
    <script>
        // Set data globally before loading the script
        window.dashboardData = {
            categories: @json($plan_categories->pluck('name')),
            amounts: @json($plan_categories->pluck('total_invested')),
            colors: @json($plan_categories->pluck('color')),
            categoryData: @json($category_data)
        };
    </script>
@endpush
