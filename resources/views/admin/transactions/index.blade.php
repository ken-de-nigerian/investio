@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <!-- Search & filter -->
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-12 col-xxl-12">
                <div class="col-12 col-md-10 col-lg-8 mb-4">
                    <h3 class="fw-normal mb-0 text-secondary">Transactions</h3>
                    <h1>You have full control to monitor all transactions on your site.</h1>
                </div>

                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-12 col-md-12 col-xxl-12 mb-4">
                        <form method="GET" action="{{ route('admin.transactions') }}" id="search-form" class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small">Search User</label>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or email...">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">-- All --</option>
                                    <option value="credit" {{ request('status') == 'credit' ? 'selected' : '' }}>Credit</option>
                                    <option value="debit" {{ request('status') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">From</label>
                                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">To</label>
                                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                            </div>

                            <div class="col-md-1">
                                <button type="submit" class="btn btn-theme w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-3">
            <!-- All Transactions Card -->
            <div class="col-12 col-lg-12 mb-4 mt-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>All Transactions</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap transfers-table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Reference ID</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td data-label="Reference ID">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $transaction->reference_id }}</p>
                                        </td>

                                        <td data-label="User">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $transaction->user->first_name }} {{ $transaction->user->last_name }}</p>
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

                                        <td data-label="Type">
                                            <span class="badge badge-sm badge-light text-bg-{{ $transaction->trans_type == 'credit' ? 'success' : ($transaction->trans_type == 'debit' ? 'danger' : '') }} small" style="font-size: 12px;">
                                                {{ ucfirst($transaction->trans_type) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found.</td>
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
