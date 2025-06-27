@extends('layouts.app')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.investment') }}">Investments</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Plans</li>
                    </ol>
                </nav>
                <h5>Plans</h5>
            </div>
        </div>

        <!-- Welcome & Investment Header -->
        <div class="row align-items-center py-4">
            <div class="col-12 col-lg-12 col-xxl-12">
                <h3 class="fw-normal mb-0 text-secondary">Investment Plans</h3>
                <h1 class="mb-4">To Grow Your Wealth</h1>

                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-12 col-md-12 col-xxl-12 mb-4">
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
                            <a href="{{ route('user.investment.categories', $category->slug) }}"
                               class="card adminuiux-card style-none text-center h-100 rounded-4 border">
                                <div class="card-body">
                                    <i class="avatar avatar-40 text-theme-1 h3 bi bi-{{ $category->icon }} mb-3"></i>
                                    <p class="text-secondary small">{{ $category->name }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- investment plans -->
            <div class="col-12 mb-4">
                <h5>Investment Plans</h5>
            </div>
            <div class="col-12 mb-5">
                @include('partials.investment-items', ['plans' => $plans])

                <!-- Pagination -->
                {{ $plans->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
