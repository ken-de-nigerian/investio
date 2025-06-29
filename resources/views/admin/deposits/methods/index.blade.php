@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center">
            <!-- Welcome box -->
            <div class="col-12 col-md-10 col-lg-8 mb-4">
                <h3 class="fw-normal mb-0 text-secondary">Payment Methods</h3>
                <h1>Streamline your payment process with ease.</h1>
            </div>
        </div>

        <br>

        <div class="row gx-3">
            <!-- Payment Methods Card -->
            <div class="col-12 col-lg-12 mb-4 mt-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Payment Methods</h6>
                            </div>

                            <div class="col-auto">
                                <a href="{{ route('admin.deposits.methods.add') }}" class="btn btn-sm btn-dark rounded-pill">Add Payment Method</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover transfers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Wallet</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($wallets as $gateway)
                                        <tr>
                                            <td data-label="Name">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $gateway['name'] }} ({{ $gateway['abbreviation'] }})</p>
                                            </td>

                                            <td data-label="Wallet">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $gateway['gateway_parameter'] }}</p>
                                            </td>

                                            <td data-label="Status">
                                                <span class="badge badge-sm badge-light text-bg-{{ $gateway['status'] == 1 ? 'success' : ($gateway['status'] == 0 ? 'danger' : '') }} small" style="font-size: 12px;">
                                                    {{ $gateway['status'] == 1 ? 'Active' : ($gateway['status'] == 0 ? 'Disabled' : '') }}
                                                </span>
                                            </td>

                                            <td data-label="Action">
                                                <a href="{{ route('admin.deposits.methods.edit', $gateway['method_code']) }}" class="btn btn-sm btn-outline-info rounded-4">Edit</a>
                                                <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteWallet({{ $gateway['method_code'] }})">Delete</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No gateways found.</td>
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
