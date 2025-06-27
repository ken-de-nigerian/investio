@extends('layouts.app')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.investment.plans') }}">Plans</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Category</li>
                    </ol>
                </nav>
                <h5>{{ $category->name }}</h5>
            </div>
        </div>

        <!-- Welcome & Investment Header -->
        <div class="row align-items-center py-4">
            <div class="col-12 col-lg-12 col-xxl-12">
                <h3 class="fw-normal mb-0 text-secondary">Investment Plans</h3>
                <h1 class="mb-4">Found in {{ $category->name }}</h1>

                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-12 col-md-12 col-xxl-12 mb-4">
                        <form method="GET" action="{{ route('user.investment.categories', $category->slug) }}" id="search-form">
                            <div class="input-group">
                                <input class="form-control border-end-0" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by plan name, or returns..." id="search-input">
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
