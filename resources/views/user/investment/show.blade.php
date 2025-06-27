@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row justify-content-center align-items-center mt-5">
            <div class="col-12 col-md-auto">
                <div class="text-center mb-3">
                    <img src="{{ asset('assets/img/investment/loan-success.png') }}" class="mw-100 mx-auto mb-3" alt="">
                    <h1 class="text-theme-1 theme-teal">Congratulations!</h1>
                    <h5 class="mb-4">Your investment has been successfully processed.</h5>
                    <p class="text-secondary">Your funds will be locked until maturity on <strong>{{ $investment->end_date->format('F j, Y') }}</strong> as per your agreement.</p>
                </div>

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
                    </div>
                </div>

                <div class="text-center mb-3">
                    <a href="{{ route('user.investment.plans') }}" class="btn btn-theme rounded-4 mx-1 my-2">Plans List</a>
                    <a href="{{ route('user.investment.list') }}" class="btn btn-outline-theme rounded-4 mx-1 my-2">Portfolio <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection
