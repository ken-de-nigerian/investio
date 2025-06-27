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
                        <li class="breadcrumb-item active bi" aria-current="page">My Goals</li>
                    </ol>
                </nav>
                <h5>My Goals</h5>
            </div>

            <div class="col-6 col-sm text-end">
                <a href="{{ route('user.goal.create') }}" class="btn btn-theme">
                    <i class="bi bi-plus-lg"></i> Create <span class="d-none d-md-inline">Goal</span>
                </a>
            </div>
        </div>

        <div class="row">
            @if($goals->count())
                @if($primary)
                    <!-- Primary Goal Card -->
                    <div class="col-12 col-lg-12 col-xxl-4 mb-4 theme-teal">
                        <div class="card adminuiux-card position-relative overflow-hidden bg-theme-1 h-100 rounded-6 border">
                            <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-25">
                                <img src="{{ asset('assets/img/modern-ai-image/tree-15.jpg') }}" alt="">
                            </div>

                            <div class="card-body z-index-1">
                                <div class="row align-items-center justify-content-center h-100 py-4">
                                    <div class="col-11 text-center">
                                        <h2 class="fw-normal text-white">Top growing goal: <strong>{{ $primary->title }}</strong></h2>

                                        <div class="height-120 width-120 position-relative d-inline-block mx-auto mb-3" data-bs-toggle="tooltip" title="You've saved ${{ number_format($primary->current_amount, 2) }} of ${{ number_format($primary->target_amount, 2) }}">
                                            <svg viewBox="0 0 100 100" style="display: block; width: 100%;">
                                                <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" stroke="rgba(255,255,255,0.2)" stroke-width="10" fill-opacity="0"></path>
                                                <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" stroke="white" stroke-width="10" fill-opacity="0" style="stroke-dasharray: 282.783; stroke-dashoffset: {{ $primary->progress_offset }}; transition: stroke-dashoffset 1s ease-in-out;"></path>
                                            </svg>

                                            <div class="progressbar-text text-white"
                                                 style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-size: 24px;">
                                                {{ $primary->progress_percentage }}<small>%</small>
                                            </div>
                                        </div>

                                        <h1 class="text-white mb-3">${{ number_format($primary->current_amount, 2) }}</h1>
                                        <p class="text-white opacity-75 mb-1">
                                            {{ $primary->progress_percentage }}% of ${{ number_format($primary->target_amount, 2) }} saved
                                        </p>

                                        <p class="text-white opacity-75 mb-1">
                                            {{ round($primary->days_remaining) }} days left • ${{ number_format($primary->monthly_target, 2) }} monthly on the {{ ordinal($primary->contribution_day) }}
                                        </p>
                                        <p class="text-white opacity-75 mb-0">Due: {{ $primary->target_date->format('jS M, Y') }}</p>

                                        <div class="d-flex justify-content-center gap-2 mt-3">
                                            <a class="btn btn-light rounded-4" onclick="confirmTopUp({{ $primary->id }})">+ Top-Up</a>
                                            <a class="btn btn-outline-light rounded-4" onclick="confirmWithdraw({{ $primary->id }})">
                                                <i class="bi bi-cash-coin align-middle"></i> Withdraw
                                            </a>
                                            <a class="btn btn-square btn-outline-light rounded-4" data-bs-toggle="tooltip" title="Delete" onclick="confirmDelete({{ $primary->id }})">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach($goals->where('id', '!=', $primary?->id) as $goal)
                    <div class="col-12 col-lg-6 col-xxl-4 mb-4">
                        <div class="card adminuiux-card {{ $goal->image_url ? 'overflow-hidden bg-theme-1' : '' }} rounded-6 border position-relative">
                            @if ($goal->image_url)
                                <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-25" style="filter: blur(3px);">
                                    <img src="{{ $goal->image_url }}" alt="">
                                </div>
                            @endif

                            <div class="card-body position-relative {{ $goal->image_url ? 'z-index-1' : '' }}">
                                <div class="row gx-3 mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-{{ $goal->category->icon }} fs-4 avatar avatar-50 text-white rounded-4" style="background-color: {{ $goal->image_url ? 'rgba(255,255,255,0.3)' : $goal->category->color }};"></i>
                                    </div>

                                    <div class="col">
                                        <h4 class="mb-0 fw-medium fs-5 {{ $goal->image_url ? 'text-white' : '' }}">{{ $goal->title }}</h4>
                                        <p class="small {{ $goal->image_url ? 'text-white opacity-75' : 'text-secondary' }}">{{ $goal->category->name }}</p>
                                    </div>
                                </div>

                                <!-- Progress -->
                                <div class="text-center mt-2 mb-3">
                                    <div class="height-120 width-120 position-relative d-inline-block mx-auto mb-3" data-bs-toggle="tooltip" title="You've saved ${{ number_format($goal->current_amount, 2) }} of ${{ number_format($goal->target_amount, 2) }}">
                                        <svg viewBox="0 0 100 100" style="display: block; width: 100%;">
                                            <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" stroke="{{ $goal->image_url ? 'rgba(255,255,255,0.2)' : 'rgba(126, 170, 0, 0.15)' }}" stroke-width="10" fill-opacity="0"></path>
                                            <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" stroke="{{ $goal->image_url ? '#fff' : 'rgb(111,170,0)' }}" stroke-width="10" fill-opacity="0" style="stroke-dasharray: 282.783; stroke-dashoffset: {{ $goal->progress_offset }}; transition: stroke-dashoffset 1s ease-in-out;"></path>
                                        </svg>

                                        <div class="progressbar-text" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-size: 24px; {{ $goal->image_url ? 'color: #fff;' : 'color: #61677a;' }}">
                                            {{ $goal->progress_percentage }}<small>%</small>
                                        </div>
                                    </div>

                                    <h2 class="mb-0 {{ $goal->image_url ? 'text-white' : '' }}">${{ number_format($goal->current_amount, 2) }}</h2>

                                    @if (!$goal->is_complete)
                                        <p class="{{ $goal->image_url ? 'text-white' : 'text-secondary' }}">{{ round($goal->days_remaining) }} days left</p>
                                    @endif
                                </div>

                                @if (!$goal->is_complete && !$goal->is_overdue)
                                    <div class="row gx-3 justify-content-center mb-3">
                                        <div class="col-auto py-2">
                                            <a class="btn rounded-4 {{ $goal->image_url ? 'btn-light' : 'btn-theme' }}" onclick="confirmTopUp({{ $goal->id }})">+ Top-Up</a>
                                        </div>
                                        <div class="col-auto py-2">
                                            <a class="btn rounded-4 {{ $goal->image_url ? 'btn-outline-light' : 'btn-outline-theme' }}"
                                               onclick="confirmWithdraw({{ $goal->id }})">
                                                <i class="bi bi-cash-coin align-middle"></i> Withdraw
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Status Message -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-bullseye text-danger h4"></i>
                                    </div>

                                    <div class="col">
                                        @if ($goal->is_complete)
                                            <p class="{{ $goal->image_url ? 'text-white opacity-75' : 'text-secondary' }} small mb-0">
                                                Completed on <strong>{{ $goal->completed_at->format('jS M, Y') }}</strong> — you saved <strong>${{ number_format($goal->current_amount, 2) }}</strong> towards your goal of <strong>${{ number_format($goal->target_amount, 2) }}</strong>. Great job!
                                            </p>
                                        @else
                                            <p class="{{ $goal->image_url ? 'text-white opacity-75' : 'text-secondary' }} small mb-0">
                                                {{ $goal->progress_percentage }}% done — keep pushing toward your <b>${{ number_format($goal->target_amount, 2) }} goal</b>, you've {{ round($goal->days_remaining) }} days left.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if (!$goal->is_complete)
                                    <div class="row align-items-center mb-3">
                                        <div class="col-auto">
                                            <i class="bi bi-calendar"></i>
                                        </div>
                                        <div class="col">
                                            <p class="{{ $goal->image_url ? 'text-white opacity-75' : 'text-secondary' }} small mb-1">Due Date</p>
                                            <p class="{{ $goal->image_url ? 'text-white' : '' }}">{{ $goal->target_date->format('jS M, Y') }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="row align-items-center mb-2">
                                    <div class="col-auto">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>

                                    <div class="col">
                                        <p class="{{ $goal->image_url ? 'text-white opacity-75' : 'text-secondary' }} small mb-1">Monthly Deposit</p>
                                        <p class="{{ $goal->image_url ? 'text-white' : '' }}">
                                            ${{ number_format($goal->monthly_target, 2) }} every {{ ordinal($goal->contribution_day) }}
                                        </p>
                                    </div>

                                    <div class="col-auto">
                                        @if ($goal->is_complete)
                                            <span class="btn btn-square rounded-4 {{ $goal->image_url ? 'btn-outline-light' : 'btn-outline-success' }}">Completed</span>
                                        @elseif ($goal->is_overdue)
                                            <span class="btn btn-square rounded-4 {{ $goal->image_url ? 'btn-outline-light' : 'btn-outline-danger' }}">Overdue</span>
                                        @endif

                                        <a class="btn btn-square rounded-4 {{ $goal->image_url ? 'btn-outline-light' : 'btn-outline-danger' }}" data-bs-toggle="tooltip" title="Delete" onclick="confirmDelete({{ $goal->id }})">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 col-lg-12 col-xxl-12 mb-4">
                    <div class="card adminuiux-card rounded-6 border position-relative border">
                        <div class="text-center">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div class="empty-notification-elem">
                                    <div class="w-25 w-sm-50 pt-3 mx-auto">
                                        <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid" alt="not-found-pic" loading="lazy" />
                                    </div>
                                    <div class="text-center pb-5 mt-2">
                                        <h6 class="fs-18 fw-semibold lh-base">No goals found.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Delete confirmation
        function confirmDelete(goal_id) {
            const form = document.getElementById('deleteForm');
            form.action = `/goal/${goal_id}/delete`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endpush
