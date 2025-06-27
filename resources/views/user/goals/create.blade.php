@extends('layouts.app')
@section('content')
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.goal') }}">My Goals</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Create Goal</li>
                    </ol>
                </nav>
                <h5>Create Goal</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
                <div class="card shadow-sm mb-4 rounded-4 border">
                    <div class="card-header border-bottom text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-crosshair"></i> Create Goal
                        </h3>
                    </div>

                    <div class="card-body">
                        <!-- Step Progress Indicator -->
                        <div class="step-wizard">
                            <div class="step-item active" data-step="1">
                                <div class="step-circle">1</div>
                                <small>Category</small>
                            </div>

                            <div class="step-item" data-step="2">
                                <div class="step-circle">2</div>
                                <small>Details</small>
                            </div>

                            <div class="step-item" data-step="3">
                                <div class="step-circle">3</div>
                                <small>Amount & Date</small>
                            </div>

                            <div class="step-item" data-step="4">
                                <div class="step-circle">4</div>
                                <small>Settings</small>
                            </div>

                            <div class="step-item" data-step="5">
                                <div class="step-circle">5</div>
                                <small>Review</small>
                            </div>
                        </div>

                        <form id="goalForm">
                            <!-- Step 1: Category Selection -->
                            <div class="step-content active" id="step-1">
                                <h4 class="mb-3 text-center">Choose Goal Category</h4>
                                <p class="text-muted mb-4 text-center mb-5">Select the category that best describes your savings goal</p>

                                <div class="row g-3" id="categoryGrid">
                                    @foreach($categories as $category)
                                        <div class="col-md-2 col-sm-6 mb-3">
                                            <div class="category-card rounded-4"
                                                 data-category="{{ $category->id }}"
                                                 data-name="{{ $category->name }}"
                                                 data-color="{{ $category->color }}"
                                                 data-icon="{{ $category->icon }}">
                                                <div class="text-center">
                                                    <i class="bi bi-{{ $category->icon }} category-icon" style="color: {{ $category->color }}"></i>
                                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Step 2: Goal Details -->
                            <div class="step-content" id="step-2">
                                <h4 class="mb-3">Goal Details</h4>
                                <div class="form-section border rounded">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control rounded-4" id="goalTitle" name="goalTitle">
                                                <div class="invalid-feedback">Goal Title is required.</div>
                                                <label for="goalTitle" class="form-label">Goal Title *</label>
                                                <div class="form-text">Enter a short, descriptive title for your goal (e.g., "Buy a Car").</div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-floating mb-3">
                                                <select class="form-select rounded-4" id="goalPriority" name="Priority">
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                    <option value="low">Low</option>
                                                </select>
                                                <label for="goalPriority" class="form-label">Priority</label>
                                                <div class="form-text">Choose the priority level for this goal.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="goalDescription" class="form-label">Description</label>
                                        <textarea class="form-control rounded-4" id="goalDescription" rows="4" placeholder="Describe your goal..."></textarea>
                                        <div class="form-text">Provide details about your goal and its significance to you.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Amount & Timeline -->
                            <div class="step-content" id="step-3">
                                <h4 class="mb-4">Amount & Timeline</h4>

                                <div class="form-section border rounded">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="targetAmount" class="form-label fw-semibold">Target Amount *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control form-control-lg" id="targetAmount"
                                                           placeholder="5000" step="0.01" min="0" required>
                                                    <div class="invalid-feedback">Target Amount is required.</div>
                                                </div>
                                                <div class="form-text">Enter the total amount you aim to save (e.g., 5000).</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="targetDate" class="form-label fw-semibold">Target Date *</label>
                                                <input type="date" class="form-control form-control-lg" id="targetDate" required>
                                                <div class="invalid-feedback">Target Date is required.</div>
                                                <div class="form-text">Select the date by which you want to achieve this goal.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="currentAmount" class="form-label fw-semibold">Current Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control form-control-lg" id="currentAmount"
                                                           placeholder="0" step="0.01" value="0" min="0">
                                                </div>
                                                <div class="form-text">Enter the amount you have already saved (if any).</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="monthlyTarget" class="form-label fw-semibold">Monthly Target
                                                    <small class="text-muted">(Optional)</small>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control form-control-lg" id="monthlyTarget"
                                                           placeholder="Auto-calculated" step="0.01" min="0">
                                                </div>
                                                <div class="form-text">Specify a monthly savings target or leave it to be auto-calculated.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress-preview border rounded" id="progressPreview">
                                    <h6>💰 Savings Calculation Preview</h6>
                                    <div id="calculationResults">
                                        <div class="empty-state">
                                            <div>📊</div>
                                            <p class="mb-0">Enter your target amount and date to see the calculation preview</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Validation Alerts -->
                                <div id="validationAlerts"></div>
                            </div>

                            <!-- Step 4: Settings -->
                            <div class="step-content" id="step-4">
                                <h4 class="mb-3">Goal Settings</h4>
                                <div class="form-section border rounded">
                                    <h6>Visibility Settings</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isPublic">
                                        <label class="form-check-label" for="isPublic">
                                            Make this goal public
                                        </label>
                                        <div class="form-text">Check this box to share your goal with others.</div>
                                    </div>
                                </div>

                                <div class="form-section border rounded">
                                    <h6>Milestones</h6>
                                    <p class="text-muted small">Set milestone percentages to track your progress</p>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input milestone-check" type="checkbox" id="milestone25" value="25" checked>
                                                <label class="form-check-label" for="milestone25">25%</label>
                                                <div class="form-text">Track when you reach 25% of your goal.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input milestone-check" type="checkbox" id="milestone50" value="50" checked>
                                                <label class="form-check-label" for="milestone50">50%</label>
                                                <div class="form-text">Track when you reach 50% of your goal.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input milestone-check" type="checkbox" id="milestone75" value="75" checked>
                                                <label class="form-check-label" for="milestone75">75%</label>
                                                <div class="form-text">Track when you reach 75% of your goal.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input milestone-check" type="checkbox" id="milestone90" value="90">
                                                <label class="form-check-label" for="milestone90">90%</label>
                                                <div class="form-text">Track when you reach 90% of your goal.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section border rounded">
                                    <h6>Goal Image (Optional)</h6>
                                    <input type="file" class="form-control form-control-lg rounded" id="goalImage" accept="image/*">
                                    <div class="form-text">Upload an image to visually represent your goal (e.g., a picture of your dream car).</div>
                                </div>
                            </div>

                            <!-- Step 5: Review -->
                            <div class="step-content" id="step-5">
                                <h4 class="mb-4">Review Your Goal</h4>

                                <div class="goal-summary">
                                    <!-- Goal Title and Description -->
                                    <div class="review-section border rounded">
                                        <div class="review-item">
                                            <span class="review-label">Goal Title</span>
                                            <span class="review-value mb-2" id="reviewTitle"></span>
                                        </div>

                                        <div class="review-item">
                                            <span class="review-label">Description</span>
                                            <span class="review-value mb-0" id="reviewDescription">Goal description...</span>
                                        </div>
                                    </div>

                                    <!-- Metrics Row -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <div class="metric-card">
                                                <small class="d-block mb-1">Target Date</small>
                                                <div class="h6 mb-0" id="reviewTargetDate">--</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="metric-card">
                                                <div id="reviewCategoryIcon" class="d-block mb-1"></div>
                                                <div class="h6 mb-0" id="reviewCategoryName"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image and Category Row -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="metric-card">
                                                <!-- Progress Preview -->
                                                <div class="progress-preview rounded">
                                                    <h6>Progress Projection</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="calculation-card">
                                                                <p class="calculation-label">Target Amount to save</p>
                                                                <div class="calculation-value" id="reviewTargetAmount">$0.00</div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="calculation-card">
                                                                <p class="calculation-label">Monthly Savings Needed</p>
                                                                <div class="calculation-value" id="reviewMonthlyTarget">$0.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="metric-card">
                                                <div id="reviewImagePreview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warning Notice -->
                                <div class="warning-notice">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-octagon me-2"></i>
                                        <strong>Important:</strong>
                                    </div>
                                    <p class="mb-0 mt-2">Once created, you won't be able to edit your goal details. Please review all information carefully before proceeding.</p>
                                </div>
                            </div>
                        </form>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display: none;">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>

                            <button type="button" class="btn btn-secondary" id="nextBtn">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>

                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="bi bi-check-lg"></i> Create Goal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
