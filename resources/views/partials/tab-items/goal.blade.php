<div class="tab-pane fade" id="pills-goal" role="tabpanel" aria-labelledby="pills-goal-tab" tabindex="0">
    <h5>Goal Calculator</h5>
    <p class="text-secondary">Calculate how much you need to save monthly to achieve your financial goal by a specific date.</p>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card adminuiux-card rounded-4 border">
                <div class="card-body">
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
                                <label for="currentAmount" class="form-label fw-semibold">Current Savings</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control form-control-lg" id="currentAmount"
                                           placeholder="0" step="0.01" value="0" min="0">
                                </div>
                                <div class="form-text">Enter the amount you have already saved toward this goal (if any).</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expectedReturn" class="form-label fw-semibold">Expected Annual Return
                                    <small class="text-muted">(Optional)</small>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg" id="expectedReturn"
                                           placeholder="5" step="0.1" min="0" value="5">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Enter the expected annual return rate (e.g., 5% for savings accounts or investments).</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="progress-preview border rounded" id="progressPreview">
                <h6>💰 Savings Goal Preview</h6>
                <div id="calculationResults">
                    <div class="empty-state">
                        <div>📊</div>
                        <p class="mb-0">Enter your target amount and date to see the savings plan preview</p>
                    </div>
                </div>
            </div>

            <!-- Validation Alerts -->
            <div id="validationAlerts"></div>
        </div>

        <div class="col-12 col-lg-12 mt-4">
            <h5>Understanding Goal-Based Savings</h5>
            <p class="text-secondary">A goal calculator helps you plan how much you need to save regularly to reach a specific financial goal, such as buying a car, funding a vacation, or making a down payment. By inputting your target amount, target date, current savings, and expected return rate, you can estimate the monthly savings required to achieve your goal.</p>

            <h6>How a Goal Calculator Can Assist You</h6>
            <p class="text-secondary">A goal calculator is a valuable tool that simplifies the process of planning your savings. It calculates the monthly savings needed based on your target amount, the time until your target date, and any expected returns from savings or investments. This tool helps you create a structured savings plan to meet your financial objectives.</p>
            <p class="text-secondary">For example, if you aim to save $10,000 for a vacation in 2 years and have $2,000 already saved, the calculator can estimate how much you need to save monthly, factoring in an expected return rate (e.g., 5% from a savings account or investment).</p>

            <h6>Benefits of Using a Goal Calculator</h6>
            <p class="text-secondary">Using a goal calculator offers several advantages for financial planning:</p>
            <ul class="lists text-secondary">
                <li><b>Clear Savings Plan:</b> Determine the exact monthly savings needed to reach your goal.</li>
                <li><b>Account for Returns:</b> Factor in potential returns from savings accounts or investments to optimize your plan.</li>
                <li><b>Track Progress:</b> Monitor how your current savings contribute to your goal and adjust your plan as needed.</li>
                <li><b>Informed Decisions:</b> Make realistic financial decisions by understanding the savings required and timeline.</li>
            </ul>

            <h6>Example of Goal-Based Savings</h6>
            <p class="text-secondary">Suppose you want to save $10,000 for a new car in 2 years, with $2,000 already saved and an expected annual return of 5%:</p>
            <ul class="lists text-secondary">
                <li><b>Target Amount:</b> $10,000</li>
                <li><b>Current Savings:</b> $2,000</li>
                <li><b>Time Period:</b> 2 years</li>
                <li><b>Expected Return:</b> 5% per year</li>
            </ul>
            <p class="text-secondary">Using a goal calculator, you might find that you need to save approximately $300 per month to reach your $10,000 goal, assuming the $2,000 grows at 5% annually. This structured approach ensures you stay on track to achieve your financial goal.</p>
            <p class="text-secondary">By using a goal calculator, you can create a tailored savings plan, monitor your progress, and make informed financial decisions to achieve your objectives efficiently.</p>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const iziToastSettings = {
            position: "topRight",
            timeout: 5000,
            resetOnHover: true,
            transitionIn: "flipInX",
            transitionOut: "flipOutX"
        };

        const showError = (message) => {
            if (typeof iziToast !== 'undefined') {
                iziToast.error({ ...iziToastSettings, message });
            } else {
                console.error(message);
            }
        };

        function setupReactiveListeners() {
            let calculationTimeout;

            // Real-time calculation with debouncing for target amount, date, and current amount
            ['targetAmount', 'currentAmount', 'targetDate'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', function() {
                        clearTimeout(calculationTimeout);
                        calculationTimeout = setTimeout(() => {
                            calculateSavings();
                            updateMonthlyTargetIfEmpty();
                        }, 300);
                    });
                }
            });

            // Handle manual monthly target input
            const monthlyTargetElement = document.getElementById('monthlyTarget');
            if (monthlyTargetElement) {
                monthlyTargetElement.addEventListener('input', function() {
                    if (this.value) {
                        updateMonthlyTargetPreview();
                    }
                });
            }
        }

        function calculateSavings() {
            const targetAmount = parseFloat(document.getElementById('targetAmount').value) || 0;
            const currentAmount = parseFloat(document.getElementById('currentAmount').value) || 0;
            const targetDateValue = document.getElementById('targetDate').value;

            // Clear previous alerts
            const validationAlertsElement = document.getElementById('validationAlerts');
            if (validationAlertsElement) {
                validationAlertsElement.innerHTML = '';
            }

            // Validation
            const validationErrors = [];

            if (currentAmount > targetAmount && targetAmount > 0) {
                validationErrors.push('Current amount cannot be greater than target amount');
            }

            if (targetAmount <= 0) {
                showEmptyState();
                return null;
            }

            if (!targetDateValue) {
                showEmptyState();
                return null;
            }

            const targetDate = new Date(targetDateValue);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time for accurate comparison

            if (targetDate <= today) {
                validationErrors.push('Target date must be in the future');
            }

            // Show validation errors
            if (validationErrors.length > 0) {
                showValidationErrors(validationErrors);
                showEmptyState();
                return null;
            }

            // Calculate savings
            const remainingAmount = Math.max(0, targetAmount - currentAmount);
            const timeDifference = targetDate - today;
            const daysRemaining = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
            const monthsRemaining = Math.max(1, Math.ceil(daysRemaining / 30));
            const weeksRemaining = Math.ceil(daysRemaining / 7);

            const monthlyRequired = remainingAmount / monthsRemaining;
            const weeklyRequired = remainingAmount / weeksRemaining;
            const dailyRequired = remainingAmount / daysRemaining;

            // Calculate progress percentage
            const progressPercentage = targetAmount > 0 ? (currentAmount / targetAmount) * 100 : 0;

            const calculationData = {
                remainingAmount,
                monthsRemaining,
                weeksRemaining,
                daysRemaining,
                monthlyRequired,
                weeklyRequired,
                dailyRequired,
                progressPercentage,
                targetAmount,
                currentAmount
            };

            // Update display
            displayCalculationResults(calculationData);

            return calculationData;
        }

        function updateMonthlyTargetIfEmpty() {
            const monthlyTargetInput = document.getElementById('monthlyTarget');
            if (!monthlyTargetInput) return;

            // Only auto-update if the field is empty or if the user hasn't manually set a value
            const calculationData = calculateSavings();
            if (calculationData && calculationData.monthlyRequired > 0) {
                if (!monthlyTargetInput.dataset.userModified) {
                    monthlyTargetInput.value = calculationData.monthlyRequired.toFixed(2);
                }
            }
        }

        function updateMonthlyTargetPreview() {
            const monthlyTarget = parseFloat(document.getElementById('monthlyTarget').value) || 0;
            const targetAmount = parseFloat(document.getElementById('targetAmount').value) || 0;
            const currentAmount = parseFloat(document.getElementById('currentAmount').value) || 0;

            if (monthlyTarget > 0 && targetAmount > 0) {
                const remainingAmount = targetAmount - currentAmount;
                const monthsToGoal = Math.ceil(remainingAmount / monthlyTarget);

                console.log(`With $${monthlyTarget}/month, you'll reach your goal in ${monthsToGoal} months`);
            }
        }

        function displayCalculationResults(data) {
            const resultsDiv = document.getElementById('calculationResults');
            if (!resultsDiv) return;

            resultsDiv.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <div class="calculation-card">
                            <p class="calculation-label">Remaining to Save</p>
                            <div class="calculation-value text-warning">$${data.remainingAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="calculation-card">
                            <p class="calculation-label">Time Remaining</p>
                            <div class="calculation-value text-info">${data.daysRemaining} days</div>
                            <small class="text-light">(${data.monthsRemaining} months, ${data.weeksRemaining} weeks)</small>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="calculation-card">
                            <p class="calculation-label">Monthly Required</p>
                            <div class="calculation-value text-success">$${data.monthlyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="calculation-card">
                            <p class="calculation-label">Weekly Required</p>
                            <div class="calculation-value text-primary">$${data.weeklyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="calculation-card">
                            <p class="calculation-label">Daily Required</p>
                            <div class="calculation-value text-secondary">$${data.dailyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-light">Progress: ${data.progressPercentage.toFixed(1)}%</small>
                        <small class="text-light">$${data.currentAmount.toLocaleString()} of $${data.targetAmount.toLocaleString()}</small>
                    </div>
                    <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar" role="progressbar"
                             style="width: ${Math.min(100, data.progressPercentage)}%; background: rgba(255,255,255,0.8);"
                             aria-valuenow="${data.progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            `;
        }

        function showEmptyState() {
            const resultsDiv = document.getElementById('calculationResults');
            if (!resultsDiv) return;

            resultsDiv.innerHTML = `
                <div class="empty-state">
                    <div>📊</div>
                    <p class="mb-0">Enter your target amount and date to see the calculation preview</p>
                </div>
            `;
        }

        function showValidationErrors(errors) {
            const alertsDiv = document.getElementById('validationAlerts');
            if (!alertsDiv) return;

            alertsDiv.innerHTML = errors.map(error => `
                <div class="alert alert-warning alert-custom mt-3" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> ${error}
                </div>
            `).join('');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date();
            const targetDateInput = document.getElementById('targetDate');
            if (targetDateInput) {
                targetDateInput.min = today.toISOString().split('T')[0];
            }

            // Initialize calculation
            calculateSavings();

            // Set up reactive listeners
            setupReactiveListeners();

            // Track when a user manually modifies monthly target
            const monthlyTargetInput = document.getElementById('monthlyTarget');
            if (monthlyTargetInput) {
                monthlyTargetInput.addEventListener('focus', function() {
                    this.dataset.userModified = 'true';
                });

                // Reset user modification flag when other inputs change significantly
                ['targetAmount', 'targetDate'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('change', function() {
                            if (monthlyTargetInput.dataset.userModified) {
                                delete monthlyTargetInput.dataset.userModified;
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush
