@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center">
            <!-- Welcome box -->
            <div class="col-12 col-md-10 col-lg-8 mb-4">
                <h3 class="fw-normal mb-0 text-secondary">Commissions</h3>
                <h1>Monitor all referral commissions earned on your site.</h1>
            </div>

            <div class="col-12 py-2"></div>

            <!-- Total registrations -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <h2>{{ $metrics['total_registrations'] ?? 0 }}</h2>
                        <p class="text-secondary small">Total Registrations</p>
                    </div>
                </div>
            </div>

            <!-- Referral earnings -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card position-relative overflow-hidden bg-theme-1 h-100 rounded-4 border">
                    <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-50">
                        <img src="{{ asset('assets/img/modern-ai-image/flamingo-4.jpg') }}" alt="Referral Earnings Background">
                    </div>
                    <div class="card-body z-index-1">
                        <div class="row gx-3 align-items-center h-100">
                            <div class="col-auto">
                                <span class="avatar avatar-60 text-bg-warning rounded">
                                    <i class="bi bi-cash-coin h4"></i>
                                </span>
                            </div>
                            <div class="col">
                                <h2>${{ number_format($metrics['referral_earnings'] ?? 0, 2) }}</h2>
                                <p>Referral Earnings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row gx-3">
            @foreach($referredUsers as $referredUser)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card adminuiux-card border mb-3 rounded-4 border">
                        <div class="card-body">
                            <div class="row gx-3">
                                <div class="col"></div>
                                <div class="col-auto">
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('admin.users.show', $referredUser->id) }}">View</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.users.edit', $referredUser->id) }}">Edit</a></li>
                                            <li><a class="dropdown-item theme-red" href="javascript:void(0)">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="avatar avatar-80 rounded-circle coverimg mb-3 mx-auto" style="background-image: url('{{ $referredUser->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($referredUser->first_name, 0, 1) . substr($referredUser->last_name, 0, 1)) }}');">
                                    <img src="{{ $referredUser->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($referredUser->first_name, 0, 1) . substr($referredUser->last_name, 0, 1)) }}" alt="{{ $referredUser->first_name ?? 'User' }}" style="display: none;" loading="lazy">
                                </div>

                                <h6 class="mb-0">
                                    <span class="position-relative">
                                        <a href="{{ route('admin.users.show', $referredUser->id) }}" class="text-decoration-none">{{ $referredUser->first_name }} {{ $referredUser->last_name }}</a>
                                    </span>
                                </h6>

                                <p class="text-secondary small">Ref by: {{ $referredUser->referrer->first_name }} {{ $referredUser->referrer->last_name }}</p>
                                <span class="badge badge-light rounded-pill text-bg-theme-1 mb-2"><i class="bi bi-envelope me-2"></i>{{ $referredUser->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Referral Commissions Card -->
            <div class="col-12 col-lg-12 mb-4 mt-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Referral Commissions</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover transfers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Investment Plan</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Referred User</th>
                                        <th scope="col">Referrer</th>
                                        <th scope="col">Commission</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($referralCommissions as $commission)
                                        <tr>
                                            <td data-label="Investment Plan">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $commission->investment->plan->name }}</p>
                                            </td>

                                            <td data-label="Date">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $commission->created_at->format('j M Y') }}</p>
                                            </td>

                                            <td data-label="Referred User">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $commission->referredUser->first_name }} {{ $commission->referredUser->last_name }}</p>
                                            </td>

                                            <td data-label="Referrer">
                                                <p class="mb-0 small" style="font-size: 12px;">{{ $commission->referrer->first_name }} {{ $commission->referrer->last_name }}</p>
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
                            {{ $referralCommissions->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
