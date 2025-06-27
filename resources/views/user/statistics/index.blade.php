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
                        <li class="breadcrumb-item active bi" aria-current="page">Statistics</li>
                    </ol>
                </nav>
                <h5>Statistics</h5>
            </div>

            <div class="col-auto py-1">
                <input type="text" class="form-control d-inline-block w-auto align-middle mx-1 border-0 bg-none" id="daterangepickerranges">
                <button class="btn btn-square btn-theme d-inline-block rounded-pill" onclick="$(this).prev().click()">
                    <i data-feather="calendar"></i>
                </button>
            </div>
        </div>

        <!-- summary -->
        <div class="row">
            <!-- cash flow chart -->
            <div class="col-12 col-lg-6 col-xl-8 mb-4">
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

            <!--  investment distribution -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body px-1">
                        <div class="w-100 height-dynamic">
                            <canvas id="investmentChart"></canvas>
                        </div>
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

            <!-- transactions history -->
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Transaction History</h6>
                            </div>

                            <div class="col-auto">
                                <a href="{{ route('user.transactions') }}" class="btn btn-sm btn btn-dark rounded-pill">See All</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover transfers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Reference ID</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td data-label="Reference ID">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $transaction->reference_id }}</p>
                                            </td>

                                            <td data-label="Date">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $transaction->created_at->format('j M Y') }}</p>
                                            </td>

                                            <td data-label="Amount">
                                                <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($transaction->amount, 2) }}</small>
                                            </td>

                                            <td data-label="Description">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $transaction->description }}</p>
                                            </td>

                                            <td data-label="Status">
                                                    <span class="badge badge-sm badge-light text-bg-{{ $transaction->trans_status == 'approved' ? 'success' : ($transaction->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                        {{ ucfirst($transaction->trans_status) }}
                                                    </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No transactions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

@push('styles')
    <style>
        .height-dynamic {
            height: 410px !important;
        }
    </style>
@endpush
