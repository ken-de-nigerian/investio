@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Calculator</li>
                    </ol>
                </nav>
                <h5>Calculator</h5>
            </div>
        </div>

        <!-- navigation -->
        <div class="position-sticky z-index-5 mb-4 adminuiux-header" style="top: 5rem;">
            <nav class="navbar rounded p-1">
                <ul class="nav nav-pills bg-none" role=tablist>
                    <li class="nav-item"><a class="nav-link active rounded-pill" href="#pills-investment" id="pills-investment-tab" data-bs-toggle="pill" role="tab" aria-controls="pills-investment" aria-selected="true">Investment</a></li>
                    <li class="nav-item mx-1"><a class="nav-link rounded-pill" href="#pills-loan" id="pills-loan-tab" data-bs-toggle="pill" role="tab" aria-controls="pills-loan" aria-selected="false">Loan</a></li>
                    <li class="nav-item"><a class="nav-link rounded-pill" href="#pills-goal" id="pills-goal-tab" data-bs-toggle="pill" role="tab" aria-controls="pills-goal" aria-selected="false">Goal</a></li>
                </ul>
            </nav>
        </div>

        <div class="tab-content mb-4" id="pills-tabContent">
            @include('partials.tab-items.investment')

            @include('partials.tab-items.loan')

            @include('partials.tab-items.goal')
        </div>
    </div>
@endsection
