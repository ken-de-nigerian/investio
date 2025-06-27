@extends('layouts.app')

@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Transaction History</li>
                    </ol>
                </nav>
                <h5>Transaction History</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Transaction History</h6>
                            </div>

                            <div class="col-auto">
                                <select class="form-select form-select-sm rounded-pill border" onchange="sortTransactions('transactions', this.value)">
                                    <option value="" {{ request()->query('sort') == '' ? 'selected' : '' }}>Sort By</option>
                                    <option value="approved" {{ request()->query('sort') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request()->query('sort') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ request()->query('sort') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
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
                            <!-- Pagination -->
                            {{ $transactions->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function sortTransactions(type, status) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', status);
            window.location.href = url.toString();
        }
    </script>
@endpush
