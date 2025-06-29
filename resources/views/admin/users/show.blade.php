@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content" style="max-width: 1500px;">
        <!-- Profile Header -->
        <div class="position-relative mx-n4 mt-n4">
            <div class="bg-dark-subtle p-5 rounded-top-4"></div>
        </div>

        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{ $user->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}" alt="user-img" class="img-thumbnail rounded-circle" />
                    </div>
                </div>

                <div class="col">
                    <div class="p-2">
                        <h3 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <i class="bi bi-geo-alt-fill me-1 text-muted fs-5 align-middle"></i>
                                {{ ucfirst(str_replace('_', ' ', $user->profile->country ?? 'N/A')) }}
                            </div>

                            <span class="badge badge-sm badge-light text-bg-{{ $user->kyc && $user->kyc->status == 'approved' ? 'success' : ($user->kyc && $user->kyc->status == 'pending' ? 'warning' : 'danger') }}">
                                <i class="bi bi-circle-fill me-1"></i>
                                {{ ucfirst($user->kyc->status ?? 'unverified') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex align-items-center mb-4 mt-4">
                        <ul class="nav flex-grow-1"></ul>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary me-2 rounded-4"><i class="bi bi-arrow-left me-1"></i> Go Back</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary rounded-4"><i class="bi bi-pencil-square me-1"></i> Edit Profile</a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-xxl-3">
                            <!-- Profile Progress -->
                            <div class="card border rounded-4 mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Profile Progress</h5>
                                </div>

                                <div class="card-body">
                                    <div class="progress" role="progressbar" aria-label="Profile completion" aria-valuenow="{{ $profile_progress }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-danger" style="width: {{ $profile_progress }}%">
                                            {{ $profile_progress }}%
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Referral Link -->
                            <div class="card border rounded-4 mb-3">
                                <div class="card-body">
                                    <input id="referralURL" type="text" value="{{ route('register', ['ref' => $user->profile->account_number ?? '']) }}" class="d-none">
                                    <button onclick="copyToClipboard(document.getElementById('referralURL'))" class="btn btn-outline-success w-100  rounded-4">
                                        <i class="bi bi-link-45deg me-1"></i> Copy Referral Link
                                    </button>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="card border rounded-4 mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Account Information</h5>
                                </div>

                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Full Name:</th>
                                                <td class="text-muted">{{ $user->first_name }} {{ $user->last_name }}</td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Mobile:</th>
                                                <td class="text-muted">{{ $user->phone_number ?? 'N/A' }}</td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">E-mail:</th>
                                                <td class="text-muted">@truncate($user->email, 20)</td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Country:</th>
                                                <td class="text-muted">{{ $user->profile->country ?? 'N/A' }}</td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Ref By:</th>
                                                <td class="text-muted">
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-circle-fill me-1"></i>
                                                        @if($user->referrer)
                                                            <a href="{{ route('admin.users.show', $user->referrer->id) }}" class="text-white text-decoration-none">
                                                                {{ $user->referrer->first_name }} {{ $user->referrer->last_name }}
                                                            </a>
                                                        @else
                                                            None
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Registered:</th>
                                                <td class="text-muted">{{ $user->created_at->format('j F Y h:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="card border rounded-4 mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Action</h5>
                                </div>

                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-primary w-sm" data-bs-toggle="modal" data-bs-target="#fundsModal">
                                            <i class="bi bi-currency-exchange me-1"></i> Manage Funds
                                        </button>

                                        <button class="btn btn-primary w-sm" data-bs-toggle="modal" data-bs-target="#emailUserModal">
                                            <i class="bi bi-envelope-fill me-1"></i> Send Email
                                        </button>

                                        <button class="btn btn-primary w-sm" data-bs-toggle="modal" data-bs-target="#userPasswordModal">
                                            <i class="bi bi-unlock-fill me-1"></i> Reset User Password
                                        </button>

                                        <form id="user-login-form" action="{{ route('admin.users.login', $user->id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-primary w-sm">
                                                <i class="bi bi-person-check-fill me-1"></i> Login As User
                                            </button>
                                        </form>

                                        <button class="btn btn-primary w-sm" data-bs-toggle="modal" data-bs-target="#blockModal">
                                            <i class="bi bi-x-circle-fill me-1"></i> Block Account
                                        </button>

                                        <button class="btn btn-primary w-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="bi bi-trash-fill me-1"></i> Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Recently Referred -->
                            <div class="card border rounded-4 mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recently Referred</h5>
                                </div>

                                <div class="card-body">
                                    @forelse($referrals->take(1) as $referral)
                                        <div class="d-flex align-items-center py-3">
                                            <div class="avatar avatar-60 flex-shrink-0 me-3">
                                                <img src="{{ $referral->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($referral->first_name, 0, 1) . substr($referral->last_name, 0, 1)) }}" alt="" class="img-fluid rounded-circle" />
                                            </div>

                                            <div class="flex-grow-1">
                                                <h5 class="fs-6 mb-1">{{ $referral->first_name }} {{ $referral->last_name }}</h5>
                                                <p class="fs-6 text-muted mb-0">Joined - {{ $referral->created_at->format('j F Y') }}</p>
                                            </div>

                                            <div class="flex-shrink-0 ms-2">
                                                <a href="{{ route('admin.users.show', $referral->id) }}" class="btn btn-sm btn-outline-success rounded-4">
                                                    <i class="bi bi-person-plus-fill"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center">
                                            <div class="empty-notification-elem">
                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                    <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid" alt="not-found-pic" loading="lazy" />
                                                </div>
                                                <div class="text-center pb-5 mt-2">
                                                    <h6 class="fs-18 fw-semibold lh-base">No referrals found.</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-xxl-9">
                            <!-- Financial Summary -->
                            <div class="card rounded-4 border mb-3">
                                <div class="card-body p-0">
                                    <div class="row row-cols-md-4 row-cols-1">
                                        <div class="col">
                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                <h5 class="text-muted text-uppercase fs-6">
                                                    Balance
                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                </h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-currency-dollar display-6 text-muted"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="mb-0">${{ number_format($user->balance, 2) }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                <h5 class="text-muted text-uppercase fs-6">
                                                    Total Deposit
                                                    <i class="bi bi-arrow-down-circle text-danger fs-5 float-end"></i>
                                                </h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-wallet display-6 text-muted"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="mb-0">${{ number_format($metric['total_deposits'], 2) }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                <h5 class="text-muted text-uppercase fs-6">
                                                    Goals & Savings
                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                </h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-arrow-left-right display-6 text-muted"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="mb-0">${{ number_format($metric['total_goals'], 2) }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-6">
                                                    Total Loans
                                                    <i class="bi bi-arrow-down-circle text-danger fs-5 float-end"></i>
                                                </h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-graph-up display-6 text-muted"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="mb-0">${{ number_format($metric['total_loans'], 2) }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <div class="card rounded-4 border mb-3">
                                <div class="card-header">
                                    <ul class="nav nav-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab', 'deposits') === 'deposits' ? 'active' : '' }}" href="?tab=deposits" data-tab="deposits">Deposits</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'transfers' ? 'active' : '' }}" href="?tab=transfers" data-tab="transfers">Transfers</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'goals' ? 'active' : '' }}" href="?tab=goals" data-tab="goals">Goals & Savings</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'loans' ? 'active' : '' }}" href="?tab=loans" data-tab="loans">Loans</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'investments' ? 'active' : '' }}" href="?tab=investments" data-tab="investments">Investments</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'referrals' ? 'active' : '' }}" href="?tab=referrals" data-tab="referrals">Referrals</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'commissions' ? 'active' : '' }}" href="?tab=commissions" data-tab="commissions">Commissions</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link rounded-pill {{ request()->query('tab') === 'transactions' ? 'active' : '' }}" href="?tab=transactions" data-tab="transactions">Transactions</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card rounded-4 border mb-3">
                                <div class="card-body">
                                    <div class="tab-content text-muted">
                                        <!-- Deposits Tab -->
                                        <div class="tab-pane {{ request()->query('tab', 'deposits') === 'deposits' ? 'active' : '' }}" id="deposits">
                                            <div class="card rounded-4 border mb-3">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-3 row-cols-1">
                                                        <div class="col">
                                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                                <h5 class="text-muted text-uppercase fs-6">
                                                                    Pending Deposits
                                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                                </h5>

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="bi bi-wallet display-6 text-muted"></i>
                                                                    </div>

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">${{ number_format($metric['pending_deposits'] ?? 0.00, 2) }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                                <h5 class="text-muted text-uppercase fs-6">
                                                                    Completed Deposits
                                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                                </h5>

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="bi bi-wallet display-6 text-muted"></i>
                                                                    </div>

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">${{ number_format($metric['completed_deposits'] ?? 0.00, 2) }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                                <h5 class="text-muted text-uppercase fs-6">
                                                                    Rejected Deposits
                                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                                </h5>

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="bi bi-wallet display-6 text-muted"></i>
                                                                    </div>

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">${{ number_format($metric['rejected_deposits'] ?? 0.00, 2) }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card rounded-4 border mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Deposit History</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap transfers-table">
                                                            <thead class="table-light">
                                                                <tr>
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

                                        <!-- Transfers Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'transfers' ? 'active' : '' }}" id="transfers">
                                            <!-- Transfers Sub-Tabs -->
                                            <div class="card rounded-4 border mb-3">
                                                <div class="card-header">
                                                    <ul class="nav nav-tabs border-bottom-0" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link rounded-pill {{ request()->query('subtab', 'interbank') === 'interbank' && request()->query('tab') === 'transfers' ? 'active' : '' }}" href="?tab=transfers&subtab=interbank" data-tab="interbank">Interbank Transfers</a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link rounded-pill {{ request()->query('subtab') === 'domestic' && request()->query('tab') === 'transfers' ? 'active' : '' }}" href="?tab=transfers&subtab=domestic" data-tab="domestic">Domestic Transfers</a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link rounded-pill {{ request()->query('subtab') === 'wire' && request()->query('tab') === 'transfers' ? 'active' : '' }}" href="?tab=transfers&subtab=wire" data-tab="wire">Wire Transfers</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="tab-content text-muted">
                                                <!-- Interbank Transfers Sub-Tab -->
                                                <div class="tab-pane {{ request()->query('subtab', 'interbank') === 'interbank' && request()->query('tab') === 'transfers' ? 'active' : '' }}" id="interbank">
                                                    <div class="card rounded-4 border mb-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">Interbank Transfers History</h5>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table align-middle table-nowrap transfers-table">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th scope="col">Recipient</th>
                                                                            <th scope="col">Bank</th>
                                                                            <th scope="col">Amount</th>
                                                                            <th scope="col">Date</th>
                                                                            <th scope="col">Status</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse($interbank_transfers as $transfer)
                                                                            <tr>
                                                                                <td data-label="Recipient">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->recipient->first_name }} {{ $transfer->recipient->last_name }}</p>
                                                                                </td>

                                                                                <td data-label="Bank">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ config('app.name') }}</p>
                                                                                </td>

                                                                                <td data-label="Amount">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">${{ number_format($transfer->amount, 2) }}</p>
                                                                                </td>

                                                                                <td data-label="Date">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->created_at->format('j F Y') }}</p>
                                                                                </td>

                                                                                <td data-label="Status">
                                                                                    <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                                                        {{ ucfirst($transfer->trans_status) }}
                                                                                    </span>
                                                                                </td>

                                                                                <td data-label="Action">
                                                                                    <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                                    <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteInterBankTransfer({{ $transfer->id }})">Delete</button>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="6" class="text-center">No interbank transfers found.</td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                                <!-- Pagination -->
                                                                {{ $interbank_transfers->links('vendor.pagination.bootstrap-5') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Domestic Transfers Sub-Tab -->
                                                <div class="tab-pane {{ request()->query('subtab') === 'domestic' && request()->query('tab') === 'transfers' ? 'active' : '' }}" id="domestic">
                                                    <div class="card rounded-4 border mb-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">Domestic Transfers History</h5>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table align-middle table-nowrap transfers-table">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th scope="col">Recipient</th>
                                                                            <th scope="col">Account Number</th>
                                                                            <th scope="col">Amount</th>
                                                                            <th scope="col">Date</th>
                                                                            <th scope="col">Status</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse($domestic_transfers as $transfer)
                                                                            <tr>
                                                                                <td data-label="Recipient">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_name }}</p>
                                                                                </td>

                                                                                <td data-label="Account Number">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->account_number }}</p>
                                                                                </td>

                                                                                <td data-label="Amount">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">${{ number_format($transfer->amount, 2) }}</p>
                                                                                </td>

                                                                                <td data-label="Date">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->created_at->format('j F Y') }}</p>
                                                                                </td>

                                                                                <td data-label="Status">
                                                                                    <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                                                        {{ ucfirst($transfer->trans_status) }}
                                                                                    </span>
                                                                                </td>

                                                                                <td data-label="Action">
                                                                                    <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                                    <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteDomesticTransfer({{ $transfer->id }})">Delete</button>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="6" class="text-center">No domestic transfers found.</td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                                <!-- Pagination -->
                                                                {{ $domestic_transfers->links('vendor.pagination.bootstrap-5') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Wire Transfers Sub-Tab -->
                                                <div class="tab-pane {{ request()->query('subtab') === 'wire' && request()->query('tab') === 'transfers' ? 'active' : '' }}" id="wire">
                                                    <div class="card rounded-4 border mb-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">Wire Transfers History</h5>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table align-middle table-nowrap transfers-table">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th scope="col">Recipient</th>
                                                                            <th scope="col">SWIFT Code</th>
                                                                            <th scope="col">Amount</th>
                                                                            <th scope="col">Date</th>
                                                                            <th scope="col">Status</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse($wire_transfers as $transfer)
                                                                            <tr>
                                                                                <td data-label="Recipient">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_name }}</p>
                                                                                </td>

                                                                                <td data-label="SWIFT Code">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_swift }}</p>
                                                                                </td>

                                                                                <td data-label="Amount">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">${{ number_format($transfer->amount, 2) }}</p>
                                                                                </td>

                                                                                <td data-label="Date">
                                                                                    <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->created_at->format('j F Y') }}</p>
                                                                                </td>

                                                                                <td data-label="Status">
                                                                                    <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                                                        {{ ucfirst($transfer->trans_status) }}
                                                                                    </span>
                                                                                </td>

                                                                                <td data-label="Action">
                                                                                    <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                                    <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteWireTransfer({{ $transfer->id }})">Delete</button>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="6" class="text-center">No wire transfers found.</td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                                <!-- Pagination -->
                                                                {{ $wire_transfers->links('vendor.pagination.bootstrap-5') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Goals & Savings Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'goals' ? 'active' : '' }}" id="goals">
                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Goals & Savings</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap investments-table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Title</th>
                                                                    <th scope="col">Target Amount</th>
                                                                    <th scope="col">Current Savings</th>
                                                                    <th scope="col">Progress</th>
                                                                    <th scope="col">Start Date</th>
                                                                    <th scope="col">End Date</th>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($goals as $goal)
                                                                    <tr>
                                                                        <td data-label="Title">
                                                                            <p class="mb-1 small">{{ $goal->title }}</p>
                                                                            <p class="small text-theme-1">
                                                                                <span class="badge badge-sm badge-light text-bg-secondary small" style="font-size: 12px;">
                                                                                <i class="bi bi-{{ $goal->category->icon }}"></i>
                                                                                    {{ $goal->category ? $goal->category->name : 'Uncategorized' }}
                                                                                </span>
                                                                            </p>
                                                                        </td>

                                                                        <td data-label="Target Amount">
                                                                            <p class="mb-0 small" style="font-size: 12px;">${{ number_format($goal->target_amount, 2) }}</p>
                                                                        </td>

                                                                        <td data-label="Current Savings">
                                                                            <p class="mb-0 small" style="font-size: 12px;">${{ number_format($goal->current_amount, 2) }}</p>
                                                                        </td>

                                                                        <td data-label="Progress">
                                                                            @php
                                                                                $progress_percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                                                                            @endphp
                                                                            <div class="progress" style="height: 6px; width: 100px;">
                                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                                     style="width: {{ $progress_percentage }}%"
                                                                                     aria-valuenow="{{ $progress_percentage }}"
                                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                                </div>
                                                                            </div>

                                                                            <p class="text-secondary small margin-top" id="progress-percentage-{{ $goal->id }}">
                                                                                {{ round($progress_percentage, 2) }}% Complete
                                                                            </p>
                                                                        </td>

                                                                        <td data-label="Start Date">
                                                                            <p class="mb-0 small" style="font-size: 12px;">{{ $goal->start_date->format('j F Y') }}</p>
                                                                        </td>

                                                                        <td data-label="End Date">
                                                                            <p class="mb-0 small" style="font-size: 12px;">{{ $goal->target_date->format('j F Y') }}</p>
                                                                        </td>

                                                                        <td data-label="Status">
                                                                            <span class="badge badge-sm badge-light text-bg-{{ $goal->status == 'active' ? 'success' : ($goal->status == 'paused' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                                                {{ ucfirst($goal->status) }}
                                                                            </span>
                                                                        </td>

                                                                        <td data-label="Action">
                                                                            <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteGoal({{ $goal->id }})">Delete</button>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">No goal found.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                        <!-- Pagination -->
                                                        {{ $goals->links('vendor.pagination.bootstrap-5') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Loans Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'loans' ? 'active' : '' }}" id="loans">
                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Loans</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap investments-table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Loan Title</th>
                                                                    <th scope="col">Amount</th>
                                                                    <th scope="col">Interest</th>
                                                                    <th scope="col">Rate</th>
                                                                    <th scope="col">Total</th>
                                                                    <th scope="col">Progress</th>
                                                                    <th scope="col">Duration</th>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($loans as $loan)
                                                                    <tr>
                                                                        <td data-label="Loan Title">
                                                                            <p class="mb-0">{{ $loan->title }}</p>
                                                                            <p class="small text-theme-1">
                                                                                {{ $loan->disbursed_at ? $loan->disbursed_at->format('j M Y') : 'Not disbursed' }}
                                                                            </p>
                                                                        </td>

                                                                        <td data-label="Amount">
                                                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($loan->loan_amount, 2) }}</small>
                                                                        </td>

                                                                        <td data-label="Interest">
                                                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($loan->total_interest ?? 0, 2) }}</small>
                                                                        </td>

                                                                        <td data-label="Rate">
                                                                            <small class="fw-normal small" style="font-size: 12px;">{{ $loan->interest_rate }}%</small>
                                                                            <span class="badge badge-sm badge-light text-bg-success mx-1 fw-normal small" style="font-size: 12px;">
                                                                                Monthly EMI
                                                                            </span>
                                                                        </td>

                                                                        <td data-label="Total">
                                                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($loan->total_payment ?? 0, 2) }}</small>
                                                                            @if($loan->status != 'completed' && $loan->loan_end_date)
                                                                                <div class="text-secondary small" id="countdown" data-end-date="{{ $loan->loan_end_date }}" style="font-size: 12px;"></div>
                                                                            @endif
                                                                        </td>

                                                                        <td data-label="Progress">
                                                                            @php
                                                                                $completion_percentage = $loan->tenure_months > 0 ? ($loan->paid_emi / $loan->tenure_months) * 100 : 0;
                                                                            @endphp
                                                                            <div class="progress" style="height: 6px; width: 100px;">
                                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                                     style="width: {{ $completion_percentage }}%"
                                                                                     aria-valuenow="{{ $completion_percentage }}"
                                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                                </div>
                                                                            </div>
                                                                            <p class="text-secondary small margin-top" id="completion-percentage-{{ $loan->id }}">
                                                                                {{ round($completion_percentage, 2) }}% Complete
                                                                            </p>
                                                                        </td>

                                                                        <td data-label="Duration">
                                                                            <span class="badge badge-sm badge-light text-bg-secondary small" style="font-size: 12px;">
                                                                                {{ $loan->tenure_months }} Months
                                                                            </span>
                                                                        </td>

                                                                        <td data-label="Status">
                                                                            @if($loan->status == 'pending')
                                                                                <span class="badge badge-sm badge-light text-bg-warning" style="font-size: 12px;">
                                                                                    {{ ucfirst($loan->status) }}
                                                                                </span>
                                                                            @elseif($loan->status == 'approved')
                                                                                <span class="badge badge-sm badge-light text-bg-success" style="font-size: 12px;">
                                                                                    {{ ucfirst($loan->status) }}
                                                                                </span>
                                                                            @elseif($loan->status == 'disbursed')
                                                                                <span class="badge badge-sm badge-light text-bg-info" style="font-size: 12px;">
                                                                                    {{ ucfirst($loan->status) }}
                                                                                </span>
                                                                            @else
                                                                                <span class="badge badge-sm badge-light text-bg-danger" style="font-size: 12px;">
                                                                                    {{ ucfirst($loan->status) }}
                                                                                </span>
                                                                            @endif
                                                                        </td>

                                                                        <td data-label="Action">
                                                                            <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteLoan({{ $loan->id }})">Delete</button>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">No loans found.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                        <!-- Pagination -->
                                                        {{ $loans->links('vendor.pagination.bootstrap-5') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Investments Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'investments' ? 'active' : '' }}" id="investments">
                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Investments</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap investments-table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Plan</th>
                                                                    <th scope="col">Amount</th>
                                                                    <th scope="col">Profit</th>
                                                                    <th scope="col">Interest</th>
                                                                    <th scope="col">Total</th>
                                                                    <th scope="col">Progress</th>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($investments as $investment)
                                                                    <tr>
                                                                        <td data-label="Plan">
                                                                            <p class="mb-0">{{ $investment->plan->name }}</p>
                                                                            <p class="small text-theme-1 mb-0">
                                                                                Started {{ $investment->start_date->format('j M Y') }}
                                                                            </p>
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
                                                                            <p class="mb-0">
                                                                                <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($profit, 2) }}</small>
                                                                            </p>

                                                                            <span class="badge badge-sm badge-light text-bg-secondary small"
                                                                                  style="font-size: 12px;">
                                                                                Matures in {{ $investment->plan->duration_display }}
                                                                            </span>
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

                                                                        <td data-label="Status">
                                                                            @if($investment->status == 'running')
                                                                                <span class="badge badge-sm badge-light text-bg-warning" style="font-size: 12px;">
                                                                                    {{ ucfirst($investment->status) }}
                                                                                </span>
                                                                            @elseif($investment->status == 'completed')
                                                                                <span class="badge badge-sm badge-light text-bg-success" style="font-size: 12px;">
                                                                                    {{ ucfirst($investment->status) }}
                                                                                </span>
                                                                            @elseif($investment->status == 'liquidated')
                                                                                <span class="badge badge-sm badge-light text-bg-success" style="font-size: 12px;">
                                                                                    {{ ucfirst($investment->status) }}
                                                                                </span>
                                                                            @else
                                                                                <span class="badge badge-sm badge-light text-bg-danger" style="font-size: 12px;">
                                                                                    {{ ucfirst($investment->status) }}
                                                                                </span>
                                                                            @endif
                                                                        </td>

                                                                        <td data-label="Action">
                                                                            <a href="#" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteInvestment({{ $investment->id }})">Delete</button>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">No $investments found.</td>
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

                                        <!-- Referrals Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'referrals' ? 'active' : '' }}" id="referrals">
                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Referred Users</h5>
                                                </div>

                                                <div class="card-body">
                                                    <table class="table table-borderless align-middle">
                                                        <tbody>
                                                            @forelse($referrals as $referral)
                                                                <tr class="border-bottom">
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ $referral->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($referral->first_name, 0, 1) . substr($referral->last_name, 0, 1)) }}" alt="" class="avatar avatar-50 rounded-circle" />
                                                                            <div class="ms-2">
                                                                                <a href="{{ route('admin.users.show', $referral->id) }}" class="text-decoration-none">
                                                                                    <h6 class="fs-6 mb-1">{{ $referral->first_name }} {{ $referral->last_name }}</h6>
                                                                                </a>
                                                                                <p class="mb-0 text-muted">Joined - {{ $referral->created_at->format('j F Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <span class="badge badge-sm badge-light small text-bg-{{ $referral->status == 'active' ? 'success' : ($referral->status == 'inactive' ? 'danger' : 'warning') }}">Active</span>
                                                                    </td>

                                                                    <td>
                                                                        <a href="{{ route('admin.users.show', $referral->id) }}" class="btn btn-sm btn-outline-success rounded-4">
                                                                            <i class="bi bi-person-plus-fill"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="2" class="text-center">No referrals found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <!-- Pagination -->
                                                    {{ $referrals->links('vendor.pagination.bootstrap-5') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Commissions Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'commissions' ? 'active' : '' }}" id="commissions">
                                            <div class="card border rounded-4 mb-3">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-2 row-cols-1">
                                                        <div class="col">
                                                            <div class="py-4 px-3 border-bottom border-end-lg">
                                                                <h5 class="text-muted text-uppercase fs-6">
                                                                    Commissions Earned
                                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                                </h5>

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="bi bi-currency-dollar display-6 text-muted"></i>
                                                                    </div>

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">${{ number_format($metric['referral_commissions'], 2) }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-6">
                                                                    Referrals
                                                                    <i class="bi bi-arrow-up-circle text-success fs-5 float-end"></i>
                                                                </h5>

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="bi bi-people display-6 text-muted"></i>
                                                                    </div>

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">{{ $metric['total_referrals'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Commissions</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap transfers-table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Investment Plan</th>
                                                                    <th scope="col">Date</th>
                                                                    <th scope="col">Referred User</th>
                                                                    <th scope="col">Commission</th>
                                                                    <th scope="col">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($commissions as $commission)
                                                                    <tr>
                                                                        <td data-label="Investment Plan">
                                                                            <p class="mb-0 small" style="font-size: 12px;">{{ $commission->investment->plan->name }}</p>
                                                                        </td>

                                                                        <td data-label="Date">
                                                                            <p class="mb-0 small" style="font-size: 12px;">{{ $commission->created_at->format('j M Y') }}</p>
                                                                        </td>

                                                                        <td data-label="Referred User">
                                                                            <p class="mb-0 small" style="font-size: 12px;">
                                                                                <a href="{{ route('admin.users.show', $commission->referredUser->id) }}" class="text-decoration-none">{{ $commission->referredUser->first_name }} {{ $commission->referredUser->last_name }}</a>
                                                                            </p>
                                                                        </td>

                                                                        <td data-label="Commission">
                                                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($commission->amount, 2) }}</small>
                                                                        </td>

                                                                        <td data-label="Status">
                                                                            <span class="badge badge-sm badge-light text-bg-success small" style="font-size: 12px;">
                                                                                Approved
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="10" class="text-center">No commissions found.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>

                                                        <!-- Pagination -->
                                                        {{ $commissions->links('vendor.pagination.bootstrap-5') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Transactions Tab -->
                                        <div class="tab-pane {{ request()->query('tab') === 'transactions' ? 'active' : '' }}" id="transactions">
                                            <div class="card rounded-4 border">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">All Transactions</h5>
                                                </div>

                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle table-nowrap transfers-table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Reference ID</th>
                                                                    <th scope="col">Date</th>
                                                                    <th scope="col">Amount</th>
                                                                    <th scope="col">Description</th>
                                                                    <th scope="col">Type</th>
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

                                                                        <td data-label="Type">
                                                                            <span class="badge badge-sm badge-light text-bg-{{ $transaction->trans_type == 'credit' ? 'success' : ($transaction->trans_type == 'debit' ? 'danger' : '') }} small" style="font-size: 12px;">
                                                                                {{ ucfirst($transaction->trans_type) }}
                                                                            </span>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Copy Referral & Wallet and Handle Forms -->
    <script>
        function copyToClipboard(inputField) {
            inputField.select();
            inputField.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(inputField.value)
                .then(() => {
                    showSuccess("Copied: " + inputField.value);
                })
                .catch(() => {
                    showError("Failed to copy. Please try again.");
                });
        }

        const formFieldMap = {
            'manage-funds-form': ['fundsAmount', 'fundsType'],
            'send-email-form': ['emailSubject', 'emailMessage'],
            'reset-password-form': ['newPassword', 'confirmPassword'],
            'block-account-form': ['blockReason'],
            'delete-account-form': ['deleteReason']
        };

        Object.entries(formFieldMap).forEach(([formId, fields]) => {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = this.querySelector('[type="submit"]');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...`;

                let isValid = true;

                fields.forEach(fieldId => {
                    const input = document.getElementById(fieldId);
                    if (!input) return;
                    const value = input.value.trim();

                    if (value === '') {
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                    return;
                }

                const formData = new FormData(this);
                const action = this.getAttribute('action');
                const method = this.getAttribute('method') || 'POST';

                formData.append('_token', '{{ csrf_token() }}');
                if (method.toUpperCase() === 'DELETE') {
                    formData.append('_method', 'DELETE');
                }

                fetch(action, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;

                        if (data.success) {
                            showSuccess(data.message || 'Action completed successfully!');
                            setTimeout(() => location.reload(), 3000);
                        } else {
                            showError(data.message || 'An error occurred. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;
                        showError('An error occurred. Please try again.');
                    });
            });

            // Remove validation feedback on input
            fields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('input', function () {
                        input.classList.remove('is-invalid');
                        input.classList.remove('is-valid');
                    });
                }
            });
        });
    </script>
@endpush
