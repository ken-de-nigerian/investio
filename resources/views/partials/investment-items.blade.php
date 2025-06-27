@forelse($plans as $plan)
    <div class="card adminuiux-card mb-3 rounded-4 position-relative border"
         data-plan-id="{{ $plan->id }}"
         data-plan-name="{{ $plan->name }}"
         data-interest-rate="{{ $plan->interest_rate }}"
         data-min-amount="{{ $plan->min_amount }}"
         data-duration-days="{{ $plan->duration_days }}"
         data-liquidity="{{ $plan->category->liquidity }}"
         data-returns-period="{{ $plan->returns_period }}">
        <span class="ribbon bg-theme-1 position-absolute top-0 end-0 z-index-1">{{ $plan->category->name }}</span>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-sm-9 col-xxl mb-3 mb-xxl-0">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-60 rounded">
                                <i class="avatar avatar-40 text-theme-1 h3 bi bi-{{ $plan->category->icon }} border rounded-4 p-4" style="color: {{ $plan->category->color }}"></i>
                            </div>
                        </div>

                        <div class="col">
                            <h5>{{ $plan->name }}</h5>
                            <span class="badge badge-light text-bg-theme-1">{{ $plan->duration_display }} Term</span>
                            <span class="badge badge-light text-bg-warning">{{ ucfirst($plan->category->risk_level) }} Risk</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-3 col-xxl-auto mb-3 mb-sm-0">
                    <h5>$ {{ number_format($plan->min_amount) }}</h5>
                    <p class="text-secondary small">Minimum Investment</p>
                </div>

                <div class="col-12 col-md-9 col-xxl-4 mb-3 mb-md-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto text-start">
                                    <h5 class="mb-1">{{ $plan->interest_rate }}%
                                        <small>
                                            <span class="badge badge-sm badge-light text-bg-success mx-1 fw-normal" style="font-size: 12px;">Paid {{ $plan->returns_period }}</span>
                                        </small>
                                    </h5>
                                    <p class="text-secondary small">{{ $plan->duration_display }} Returns*</p>
                                </div>

                                <div class="col-auto">
                                    <i class="bi bi-plus-lg"></i>
                                </div>

                                <div class="col-auto text-end">
                                    <h5>$ {{ number_format($plan->projected_return, 2) }}</h5>
                                    <p class="text-secondary small">Projected Return</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <button onclick="processInvestment({{ $plan->id }})" class="btn rounded-4" style="border-color: {{ $plan->category->color }}; color: {{ $plan->category->color }}">Perform Investment</button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card adminuiux-card rounded-6 border position-relative border">
        <div class="text-center">
            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                <div class="empty-notification-elem">
                    <div class="w-25 w-sm-50 pt-3 mx-auto">
                        <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid" alt="not-found-pic" loading="lazy" />
                    </div>
                    <div class="text-center pb-5 mt-2">
                        <h6 class="fs-18 fw-semibold lh-base">No plans found.</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforelse
