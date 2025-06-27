@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house-door me-1 fs-14"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active bi" aria-current="page">My Wallet</li>
                    </ol>
                </nav>
                <h5>My Wallet</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                @php
                    $hour = now()->hour;
                    $greeting = match (true) {
                        $hour >= 0 && $hour < 6 => 'Good Morning',
                        $hour >= 6 && $hour < 12 => 'Good Morning',
                        $hour >= 12 && $hour < 17 => 'Good Afternoon',
                        $hour >= 17 && $hour < 22 => 'Good Evening',
                        default => 'Good Night',
                    };
                @endphp
                <h3 class="fw-normal mb-0 text-secondary">{{ $greeting }},</h3>
                <h1 class="mb-3 mb-md-5">{{ $auth['user']->first_name }} {{ $auth['user']->last_name }}</h1>

                <div class="row align-items-center">
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

            <!-- balance -->
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-body z-index-1">
                        <div class="row gx-2 align-items-center mb-4">
                            <div class="col-auto py-1">
                                <div class="avatar avatar-60 bg-white-opacity rounded-pill">
                                    <i class="bi bi-wallet h2"></i>
                                </div>
                            </div>

                            <div class="col px-0"></div>

                            <div class="col-auto py-2">
                                <a class="btn btn-lg btn-square btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#sendmoneymodal" title="Send Money"><i class="bi bi-arrow-up-right"></i></a>
                            </div>

                            <div class="col-auto py-2">
                                <a class="btn btn-lg btn-square btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#addmoneymodal" title="Add Money"><i class="bi bi-plus-lg"></i></a>
                            </div>

                            <div class="col-auto py-2">
                                <a href="{{ route('user.domestic.transfer') }}" class="btn btn-lg btn-square btn-outline-secondary rounded-pill" title="Domestic Transfer"><i class="bi bi-calendar-event"></i></a>
                            </div>

                            <div class="col-auto py-2">
                                <a href="{{ route('user.wire.transfer') }}" class="btn btn-lg btn-square btn-outline-secondary rounded-pill" title="Wire Transfer"><i class="bi bi-globe-americas"></i></a>
                            </div>
                        </div>

                        <h1 class="wallet-balance">${{ number_format($auth['user']->balance ?? 0, 2) }}</h1>
                        <h5 class="opacity-75 fw-normal mb-1">Your total balance</h5>
                    </div>

                    <div class="position-absolute bottom-0 end-0 opacity-25">
                        <i class="bi bi-stars display-4"></i>
                    </div>
                </div>
            </div>

            <!-- chart -->
            <div class="col-12 col-md-12 col-lg-12 mb-4">
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

            <!-- start investment -->
            <div class="col-12">
                <div class="row mb-2">
                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a data-bs-toggle="modal" data-bs-target="#sendmoneymodal" title="Send Money"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 h3 bi bi-bank mb-3"></i>
                                <p class="text-secondary small">Same Bank Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.domestic.transfer') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-calendar-event h3 mb-3"></i>
                                <p class="text-secondary small">Domestic Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.wire.transfer') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-globe-americas h3 mb-3"></i>
                                <p class="text-secondary small">Wire Transfer</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.loan') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-cash-stack h3 mb-3"></i>
                                <p class="text-secondary small">Loan Financing</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.profile', ['tab' => 'cards']) }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-credit-card-2-front-fill h3 mb-3"></i>
                                <p class="text-secondary small">Virtual Cards</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.goal') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-tags h3 mb-3"></i>
                                <p class="text-secondary small">My Goals</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.investment') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-piggy-bank h3 mb-3"></i>
                                <p class="text-secondary small">Investments</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-xxl mb-3">
                        <a href="{{ route('user.statistics') }}"
                           class="card adminuiux-card style-none text-center h-100 border rounded-4">
                            <div class="card-body">
                                <i class="avatar avatar-40 text-theme-1 bi bi-bar-chart h3 mb-3"></i>
                                <p class="text-secondary small">Statistics</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- emi transaction -->
            <div class="col-12 col-lg-12 col-xl-6 col-xxl-12">
                <div class="row">
                    @foreach($loans as $loan)
                        @php
                            // Define theme class based on loan status
                            $themes = [
                                'pending' => 'theme-yellow',
                                'approved' => 'adminuiux-card',
                                'rejected' => 'theme-red',
                                'disbursed' => 'theme-purple',
                                'completed' => 'theme-green',
                            ];
                            $theme = $themes[$loan->status] ?? 'theme-yellow';

                            // Dynamic label/title for the card
                            $statusTitles = [
                                'pending' => 'Processing...',
                                'approved' => $loan->title,
                                'rejected' => 'Rejected',
                                'disbursed' => 'Disbursed',
                                'completed' => $loan->title,
                            ];
                            $loan_title = $statusTitles[$loan->status] ?? 'Pending';

                            $link = route('user.loan.show', $loan->id);
                            $isApprovedOrCompleted = in_array($loan->status, ['approved', 'completed']);
                            $icon = $isApprovedOrCompleted ? 'bi-house' : 'bi-arrow-clockwise';
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 col-xl-6 col-xxl-3">
                            <div class="card border-theme-1 {{ $theme }} mb-4">
                                <div class="card-header bg-theme-1-subtle border-bottom">
                                    <div class="row gx-3 align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 bg-theme-1-subtle text-theme-1 rounded">
                                                <i class="bi {{ $icon }} h5"></i>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <a href="{{ $link }}" class="style-none text-theme-1">
                                                <h5 class="mb-0">@truncate($loan->title, 25)</h5>
                                            </a>

                                            <p class="text-secondary small">
                                                Loan
                                                Account: {{ hidePhoneNumber($loan->user->profile->account_number) ?? 'xx-xxxx' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="card-body z-index-1 {{ $loan->status === 'completed' ? 'bg-theme-1-subtle' : '' }}">
                                    <div class="row gx-3 align-items-center">
                                        <div class="col">
                                            <h4 class="fw-medium mb-0">
                                                $ {{ number_format($loan->loan_amount ?? 0, 2) }}</h4>
                                            @if($loan->status === 'completed')
                                                <p class="text-secondary small">
                                                    This loan is fully paid & completed
                                                    on {{ Carbon::parse($loan->completed_at)->format('jS F Y') }}
                                                </p>
                                            @elseif($loan->status === 'approved')
                                                <p class="text-secondary small">
                                                    Paid {{ $loan->paid_emi }} <span
                                                        class="opacity-75">/{{ $loan->tenure_months }}</span>
                                                    EMI,
                                                    Interest Rate {{ '$' . number_format($loan->total_interest, 2) }}
                                                </p>
                                            @else
                                                <p class="text-secondary small">
                                                    ${{ number_format($loan->monthly_emi ?? 140, 2) }} EMI,
                                                    Interest Rate {{ '$' . number_format($loan->total_interest, 2) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="col-auto">
                                            <a href="{{ $link }}" class="btn btn-square btn-link">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- recent transaction -->
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Recent Transactions</h6>
                            </div>

                            <div class="col-auto px-0">
                                <a href="{{ route('user.transactions') }}" class="btn btn-sm btn btn-link">See All</a>
                            </div>
                        </div>
                    </div>

                    <!-- recent transaction list -->
                    <ul class="list-group list-group-flush border-top bg-none">
                        @forelse($transactions as $transaction)
                            <li class="list-group-item @if($transaction->trans_type === 'credit') theme-green @else theme-red @endif">
                                <div class="row gx-3 align-items-center">
                                    <div class="col-auto">
                                        <div
                                            class="avatar avatar-40 rounded-circle border border-theme-1 bg-theme-1-subtle text-theme-1">
                                            @if($transaction->trans_type === 'credit')
                                                <i class="bi bi-arrow-up-right h5"></i>
                                            @else
                                                <i class="bi bi-arrow-down-left h5"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col">
                                        <p class="mb-1 fw-medium">{{ $transaction->description }}</p>
                                        <p class="text-secondary small">{{ $transaction->created_at->format('j F Y, g:i A') }}</p>
                                    </div>

                                    <div class="col-auto">
                                        @if($transaction->trans_type === 'credit')
                                            <h6 class="text-theme-1">+ $ {{ number_format($transaction->amount) }}</h6>
                                        @else
                                            <h6 class="text-theme-1">- $ {{ number_format($transaction->amount) }}</h6>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="row gx-3 align-items-center">
                                    <div class="text-center">
                                        <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab"
                                             role="tabpanel">
                                            <div class="empty-notification-elem">
                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                    <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid"
                                                         alt="not-found-pic" loading="lazy"/>
                                                </div>

                                                <div class="text-center pb-5 mt-2">
                                                    <h6 class="fs-18 fw-semibold lh-base">No transactions found.</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- goals and Savings -->
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="card adminuiux-card border rounded-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Goals And Saving</h6>
                            </div>

                            <div class="col-auto px-0">
                                <a href="{{ route('user.goal') }}" class="btn btn-sm btn btn-link">See All</a>
                            </div>
                        </div>
                    </div>

                    <!-- list -->
                    <ul class="list-group list-group-flush border-top bg-none">
                        @forelse($goals as $goal)
                            <li class="list-group-item {{ $goal->progress_percentage == 100 ? 'theme-green' : '' }}">
                                <div class="row gx-3 align-items-center">
                                    <div class="col-auto">
                                        <i class="bi bi-{{ $goal->category->icon }} avatar avatar-40 h5 rounded-circle border"></i>
                                    </div>

                                    <div class="col">
                                        <p class="mb-1 fw-medium">@truncate($goal->title, 25)</p>
                                        <p class="small text-secondary">
                                            $ {{ number_format($goal->current_amount, 2) }}</p>
                                    </div>

                                    <div class="col-auto text-end">
                                        <h6>$ {{ number_format($goal->target_amount, 2) }}</h6>
                                        <p class="small text-secondary {{ $goal->progress_percentage == 100 ? 'text-theme-1' : '' }}">{{ $goal->progress_percentage }}
                                            % Completed</p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="row gx-3 align-items-center">
                                    <div class="text-center">
                                        <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab"
                                             role="tabpanel">
                                            <div class="empty-notification-elem">
                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                    <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid"
                                                         alt="not-found-pic" loading="lazy"/>
                                                </div>

                                                <div class="text-center pb-5 mt-2">
                                                    <h6 class="fs-18 fw-semibold lh-base">No goals found.</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(inputField) {
            // Select the text inside the input field
            inputField.select();
            inputField.setSelectionRange(0, 99999); /* For mobile devices */

            const showError = (message) => {
                iziToast.error({ ...iziToastSettings, message });
            };

            const showSuccess = (message) => {
                iziToast.success({ ...iziToastSettings, message });
            };

            // Use Clipboard API to copy text
            navigator.clipboard.writeText(inputField.value)
                .then(() => {
                    // If copying was successful
                    showSuccess("Copied: " + inputField.value);
                })
                .catch(() => {
                    // If copying failed
                    showError("Failed to copy. Please try again.");
                });
        }
    </script>
@endpush
