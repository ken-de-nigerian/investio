<div class="tab-pane fade show active" id="pills-investment" role="tabpanel" aria-labelledby="pills-investment-tab" tabindex="0">
    <h5>Investment Calculator</h5>
    <p class="text-secondary">Calculate the potential returns on your investment based on the selected plan and amount.</p>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card adminuiux-card mb-4 border rounded-4">
                <div class="card-body height-270">
                    <div class="mb-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-sm mb-3">
                                <p>Investment Amount</p>
                            </div>
                            <div class="col-12 col-sm-auto mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-none">$</span>
                                    <input type="text" class="form-control text-end rangevalues" value="10000" id="value1">
                                </div>
                            </div>
                        </div>
                        <input type="range" id="range1" class="range1 rangevalue" min="100" max="150000" value="10000" data-value="value1">
                    </div>

                    <div class="mb-4">
                        <div class="form-floating mb-4">
                            <select class="form-select rounded-4" id="plan" name="plan">
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option
                                        value="{{ $plan->id }}"
                                        data-plan-id="{{ $plan->id }}"
                                        data-plan-name="{{ $plan->name }}"
                                        data-interest-rate="{{ $plan->interest_rate }}"
                                        data-min-amount="{{ $plan->min_amount }}"
                                        data-duration-days="{{ $plan->duration_days }}"
                                        data-liquidity="{{ $plan->category->liquidity }}"
                                        data-returns-period="{{ $plan->returns_period }}"
                                    >{{ $plan->name }}</option>
                                @endforeach
                            </select>
                            <label for="plan">Investment Plan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card adminuiux-card mb-4 border rounded-4">
                <div class="card-body">
                    <div class="text-center mt-2 mb-4">
                        <h2 class="mb-0" id="totalValue">$ 0</h2>
                        <p class="text-secondary">Total Value</p>
                    </div>

                    <div class="row justify-content-between mb-4">
                        <div class="col-auto">
                            <p>
                                <span class="avatar avatar-20 rounded-circle bg-theme-1-subtle align-middle me-2"></span>
                                <span class="d-inline-block align-middle">Investment Amount<br><small class="text-secondary" id="investmentAmount">$ 10,000</small></span>
                            </p>
                        </div>

                        <div class="col-auto">
                            <p>
                                <span class="avatar avatar-20 rounded-circle bg-theme-1 align-middle me-2"></span>
                                <span class="d-inline-block align-middle">Projected Return<br><small class="text-secondary" id="projectedReturn">$ 0</small></span>
                            </p>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <p class="text-secondary mb-1">Maturity Date</p>
                        <p class="fw-bold" id="maturityDateDisplay">--</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-12 mt-4">
            <h5>Investment Calculator - Plan Your Wealth</h5>
            <p class="text-secondary">An investment calculator helps you estimate the potential growth of your investment based on the amount you invest and the selected investment plan. By choosing a plan and adjusting the investment amount, you can see the projected returns and total value over the plan's duration.</p>

            <h6>What is an Investment Calculator?</h6>
            <p class="text-secondary">An investment calculator is a user-friendly tool that provides an estimate of your investment's growth based on the plan's interest rate, duration, and compounding frequency. It helps you understand how your money can grow over time, depending on the investment plan you choose.</p>
            <p class="text-secondary">This calculator provides a rough estimate of the total value, including your initial investment and projected returns. Note that actual returns may vary due to market conditions, fees, or other factors.</p>

            <h6>How Can an Investment Calculator Help You?</h6>
            <p class="text-secondary">Using an investment calculator offers several advantages for financial planning:</p>
            <ul class="lists text-secondary">
                <li><b>Estimates Growth:</b> Calculate the projected returns based on the investment amount and plan details.</li>
                <li><b>Compares Plans:</b> Evaluate different investment plans to choose the one that best aligns with your financial goals.</li>
                <li><b>Supports Planning:</b> Plan your investments by understanding the total value you can expect at the end of the plan's duration.</li>
            </ul>
            <p class="text-secondary">By leveraging an investment calculator, you can make informed decisions about your investments, ensuring you choose the right plan to achieve your financial objectives.</p>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Global variables
        let currentPlan = null;
        let countdownInterval = null;

        // Helper function to get plan data from select option
        function getPlanData(planId) {
            const option = document.querySelector(`option[data-plan-id="${planId}"]`);
            if (option) {
                return {
                    id: planId,
                    name: option.dataset.planName,
                    interest_rate: parseFloat(option.dataset.interestRate),
                    min_amount: parseFloat(option.dataset.minAmount),
                    duration_days: parseInt(option.dataset.durationDays),
                    liquidity: option.dataset.liquidity,
                    returns_period: option.dataset.returnsPeriod || 'Monthly'
                };
            }
            return null;
        }

        // Calculate maturity date
        function calculateMaturityDate(durationDays) {
            const maturityDate = new Date();
            maturityDate.setDate(maturityDate.getDate() + durationDays);
            return maturityDate;
        }

        // Format date for display
        function formatDateDisplay(date) {
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Calculate projected return based on plan
        function calculateProjectedReturn(amount, plan) {
            if (!amount || isNaN(amount) || !plan) {
                return 0;
            }

            const annualRate = plan.interest_rate / 100;
            let projectedReturn;

            switch(plan.liquidity) {
                case 'daily':
                    projectedReturn = amount * Math.pow(1 + (annualRate/365), plan.duration_days);
                    break;
                case 'weekly':
                    projectedReturn = amount * Math.pow(1 + (annualRate/52), plan.duration_days/7);
                    break;
                case 'monthly':
                    projectedReturn = amount * Math.pow(1 + (annualRate/12), plan.duration_days/(365/12));
                    break;
                case 'term':
                    projectedReturn = amount * (1 + annualRate * (plan.duration_days/365));
                    break;
                default:
                    projectedReturn = amount * Math.pow(1 + annualRate, plan.duration_days/365);
            }

            return projectedReturn;
        }

        // Update range input constraints based on plan
        function updateRangeConstraints(plan) {
            const rangeInput = document.getElementById('range1');
            const valueInput = document.getElementById('value1');

            if (plan) {
                // Set minimum to plan's minimum amount
                rangeInput.min = plan.min_amount;

                // Set maximum to a very high value for "unlimited" effect
                rangeInput.max = 10000000; // 10 million as practical unlimited

                // If current value is below minimum, adjust it
                const currentValue = parseFloat(valueInput.value) || 0;
                if (currentValue < plan.min_amount) {
                    rangeInput.value = plan.min_amount;
                    valueInput.value = plan.min_amount;
                }
            } else {
                // Reset to default values when no plan selected
                rangeInput.min = 100;
                rangeInput.max = 150000;

                // Ensure current value is within default range
                const currentValue = parseFloat(valueInput.value) || 0;
                if (currentValue > 150000) {
                    rangeInput.value = 150000;
                    valueInput.value = 150000;
                } else if (currentValue < 100) {
                    rangeInput.value = 100;
                    valueInput.value = 100;
                }
            }
        }

        // Validate input amount against plan constraints
        function validateAmount(amount, plan) {
            if (!plan) return true;

            return amount >= plan.min_amount;
        }

        // Update calculator display
        function updateCalculatorDisplay(amount, plan) {
            const investmentAmountEl = document.getElementById('investmentAmount');
            const projectedReturnEl = document.getElementById('projectedReturn');
            const totalValueEl = document.getElementById('totalValue');
            const maturityDateEl = document.getElementById('maturityDateDisplay');

            // Validate amount if plan is selected
            if (plan && !validateAmount(amount, plan)) {
                // Show error state or adjust to minimum
                investmentAmountEl.textContent = `$ ${parseFloat(amount).toLocaleString()} (Min: $${plan.min_amount.toLocaleString()})`;
                projectedReturnEl.textContent = '$ 0';
                totalValueEl.textContent = '$ 0';
                maturityDateEl.textContent = '--';
                return;
            }

            // Format and display investment amount
            investmentAmountEl.textContent = `$ ${parseFloat(amount).toLocaleString()}`;

            if (plan) {
                // Calculate and display projected return
                const projectedTotal = calculateProjectedReturn(amount, plan);
                const profit = projectedTotal - amount;

                projectedReturnEl.textContent = `$ ${profit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                totalValueEl.textContent = `$ ${projectedTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                // Calculate and display maturity date
                const maturityDate = calculateMaturityDate(plan.duration_days);
                maturityDateEl.textContent = formatDateDisplay(maturityDate);
            } else {
                projectedReturnEl.textContent = '$ 0';
                totalValueEl.textContent = `$ ${parseFloat(amount).toLocaleString()}`;
                maturityDateEl.textContent = '--';

                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            const rangeInput = document.getElementById('range1');
            const valueInput = document.getElementById('value1');
            const planSelect = document.getElementById('plan');

            // Sync range and input
            rangeInput.addEventListener('input', function() {
                valueInput.value = this.value;
                updateCalculatorDisplay(this.value, currentPlan);
            });

            valueInput.addEventListener('input', function() {
                let value = parseFloat(this.value) || 0;

                // If plan is selected and value is below minimum, enforce minimum
                if (currentPlan && value < currentPlan.min_amount) {
                    value = currentPlan.min_amount;
                    this.value = value;
                }

                // Update range input, but don't constrain to max for unlimited effect
                if (currentPlan) {
                    rangeInput.value = Math.min(value, rangeInput.max);
                } else {
                    rangeInput.value = Math.min(value, rangeInput.max);
                }

                updateCalculatorDisplay(value, currentPlan);
            });

            // Handle plan selection
            planSelect.addEventListener('change', function() {
                if (this.value) {
                    currentPlan = getPlanData(this.value);
                    updateRangeConstraints(currentPlan);
                    updateCalculatorDisplay(valueInput.value, currentPlan);
                } else {
                    currentPlan = null;
                    updateRangeConstraints(null);
                    updateCalculatorDisplay(valueInput.value, null);
                }
            });

            // Initial display update
            updateCalculatorDisplay(valueInput.value, null);
        });
    </script>
@endpush
