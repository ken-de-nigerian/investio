@extends('layouts.admin')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Deposits</li>
                    </ol>
                </nav>
                <h5>Deposits</h5>
            </div>
        </div>

        <div class="row">
            <!-- approved deposits -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-teal mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['approved_deposits'], 2) }}</h4>
                        <p><span class="text-secondary">Approved Deposits</span></p>
                    </div>
                </div>
            </div>

            <!-- pending deposits -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-orange mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['pending_deposits'], 2) }}</h4>
                        <p><span class="text-secondary">Pending Deposits</span></p>
                    </div>
                </div>
            </div>

            <!-- rejected deposits -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-red mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['rejected_deposits'], 2) }}</h4>
                        <p><span class="text-secondary">Rejected Deposits</span></p>
                    </div>
                </div>
            </div>

            <!-- Deposit history -->
            <div class="col-12 col-md-12 col-xl-12 col-xxl-12 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Deposit History</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap transfers-table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Currency</th>
                                        <th scope="col">Crypto Value</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td data-label="User">
                                            <p class="mb-0 small" style="font-size: 12px;">
                                                <a href="{{ route('admin.users.show', $deposit->user->id) }}" class="text-decoration-none">
                                                    {{ $deposit->user->first_name }} {{ $deposit->user->last_name }}
                                                </a>
                                            </p>
                                        </td>

                                        <td data-label="Currency">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ getWallet($deposit->payment_method) }}</p>
                                        </td>

                                        <td data-label="Crypto Value">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $deposit->converted_amount }}</p>
                                        </td>

                                        <td data-label="Amount">
                                            <p class="mb-0 small" style="font-size: 12px;">${{ number_format($deposit->amount, 2) }}</p>
                                        </td>

                                        <td data-label="Date">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $deposit->created_at->format('j F Y') }}</p>
                                        </td>

                                        <td data-label="Status">
                                            <span class="badge badge-sm badge-light text-bg-{{ $deposit->status == 'approved' ? 'success' : ($deposit->status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                {{ ucfirst($deposit->status) }}
                                            </span>
                                        </td>

                                        <td data-label="Action">
                                            <a href="{{ route('admin.deposits.show', $deposit->id) }}" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteDeposit({{ $deposit->id }})">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No deposits found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            {{ $deposits->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
