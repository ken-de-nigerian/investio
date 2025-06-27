<div class="tab-pane fade" id="pills-loan" role="tabpanel" aria-labelledby="pills-loan-tab" tabindex="0">
    <h5>Loan Calculator</h5>
    <p class="text-secondary">Calculate your monthly EMI and total repayment for a loan to plan your finances effectively.</p>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card adminuiux-card mb-4 border rounded-4">
                <div class="card-body">
                    <!-- Loan Amount -->
                    <div class="mb-3">
                        <label class="form-label" for="loanAmountInput">Loan Amount</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control rangevalues" id="loanAmountInput"
                                   name="loan_amount" min="{{ config('settings.loan.min_amount', 1000) }}"
                                   max="{{ config('settings.loan.max_amount', 100000) }}"
                                   value="{{ config('settings.loan.min_amount', 1000) }}"
                                   aria-describedby="loanAmountHelp" required>
                        </div>

                        <div id="loanAmountHelp" class="form-text">Enter an amount between
                            ${{ config('settings.loan.min_amount', 1000) }} and
                            ${{ config('settings.loan.max_amount', 100000) }}.
                        </div>

                        <input type="range" class="form-range" id="loanAmountRange"
                               min="{{ config('settings.loan.min_amount', 1000) }}"
                               max="{{ config('settings.loan.max_amount', 100000) }}"
                               value="{{ config('settings.loan.min_amount', 1000) }}"
                               aria-label="Loan amount range">
                    </div>

                    <!-- Tenure -->
                    <div class="mb-3">
                        <label class="form-label" for="tenureInput">Loan Tenure (Months)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Months</span>
                            <input type="number" class="form-control rangevalues" id="tenureInput"
                                   name="tenure_months" min="1"
                                   max="{{ config('settings.loan.repayment_period', 60) }}" value="1"
                                   aria-describedby="tenureHelp" required>
                        </div>

                        <div id="tenureHelp" class="form-text">Select a tenure between 1 and
                            {{ config('settings.loan.repayment_period', 60) }} months.
                        </div>

                        <input type="range" class="form-range" id="tenureRange" min="1"
                               max="{{ config('settings.loan.repayment_period', 60) }}" value="1"
                               aria-label="Loan tenure range">
                    </div>

                    <!-- Interest Rate -->
                    <div class="mb-3">
                        <label class="form-label" for="interestRateInput">Annual Interest Rate (%)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text">%</span>
                            <input type="number" class="form-control rangevalues" id="interestRateInput"
                                   name="interest_rate" step="0.1"
                                   value="{{ config('settings.loan.interest_rate', 5) }}"
                                   aria-describedby="interestRateHelp" required>
                        </div>

                        <div id="interestRateHelp" class="form-text">Enter an interest rate between 0% and 50%.</div>
                        <input type="range" class="form-range" id="interestRateRange" min="0" max="50" step="0.1"
                               value="{{ config('settings.loan.interest_rate', 5) }}" aria-label="Interest rate range">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card adminuiux-card mb-4 border rounded-4">
                <div class="card-body height-dynamic">
                    <div class="text-center mb-3 position-relative" style="min-height: 200px;">
                        <canvas id="emiChart" style="max-height: 200px;" aria-label="Pie chart showing principal and interest breakdown"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h4 id="totalPayment" class="mb-0 fw-bold">N/A</h4>
                            <p class="text-muted small">Total Repayment</p>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <h6>Your EMI will be</h6>
                        <h1 class="text-theme-1" id="emiResult">N/A <small class="fs-6 fw-normal">/month</small></h1>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <p class="text-secondary small mb-1">
                                <span class="d-inline-block bg-theme-1 rounded-circle me-1" style="width:10px; height:10px;"></span>
                                Principal
                            </p>
                            <h5 id="principalAmount">N/A</h5>
                        </div>

                        <div class="col-6">
                            <p class="text-secondary small mb-1">
                                <span class="d-inline-block bg-theme-1-subtle rounded-circle me-1" style="width:10px; height:10px;"></span>
                                Interest
                            </p>
                            <h5 id="totalInterest">N/A</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-12 mt-4">
            <h5>Understanding Loan Calculations</h5>
            <p class="text-secondary">A loan calculator helps you estimate your monthly EMI, total interest payable, and overall repayment amount. By inputting the loan amount, tenure, and interest rate, you can gain insights into your financial obligations and plan your budget effectively.</p>

            <h6>Benefits of Using a Loan Calculator</h6>
            <p class="text-secondary">A loan calculator simplifies the process of understanding your loan repayment structure. Here are the key benefits:</p>
            <ul class="lists text-secondary">
                <li><b>Accurate EMI Estimation:</b> Calculate your monthly EMI based on the loan amount, tenure, and interest rate.</li>
                <li><b>Total Interest Payable:</b> Understand the total interest you will pay over the loan tenure.</li>
                <li><b>Financial Planning:</b> Plan your finances by visualizing the breakdown of principal and interest components.</li>
                <li><b>Informed Borrowing Decisions:</b> Compare different loan amounts, tenures, and interest rates to choose the most suitable loan option.</li>
            </ul>

            <h6>How a Loan Calculator Works</h6>
            <p class="text-secondary">The loan calculator uses the following key components to compute your EMI and repayment details:</p>
            <ul class="lists text-secondary">
                <li><b>Principal Amount:</b> The total loan amount you borrow.</li>
                <li><b>Interest Rate:</b> The annual interest rate charged by the lender, converted to a monthly rate for calculations.</li>
                <li><b>Loan Tenure:</b> The duration in months over which you will repay the loan.</li>
                <li><b>EMI (Equated Monthly Installment):</b> The fixed monthly payment that includes both principal and interest components.</li>
                <li><b>Total Repayment:</b> The sum of all EMIs, which includes the principal and total interest paid over the loan tenure.</li>
            </ul>
            <p class="text-secondary">Using a loan calculator empowers you to make informed borrowing decisions, optimize your repayment strategy, and achieve your financial goals with confidence.</p>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const loanAmountInput = document.getElementById('loanAmountInput');
        const loanAmountRange = document.getElementById('loanAmountRange');

        const tenureInput = document.getElementById('tenureInput');
        const tenureRange = document.getElementById('tenureRange');

        const interestRateInput = document.getElementById('interestRateInput');
        const interestRateRange = document.getElementById('interestRateRange');

        const emiResult = document.getElementById('emiResult');
        const totalInterestEl = document.getElementById('totalInterest');
        const totalPaymentEl = document.getElementById('totalPayment');
        const principalAmountEl = document.getElementById('principalAmount');

        let chartInstance;

        function debounce(fn, delay) {
            let timeout;
            return function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, arguments), delay);
            };
        }

        function syncInputAndRange(input, range) {
            input.addEventListener('input', () => {
                range.value = input.value;
                triggerDebouncedUpdate();
            });

            range.addEventListener('input', () => {
                input.value = range.value;
                triggerDebouncedUpdate();
            });
        }

        syncInputAndRange(loanAmountInput, loanAmountRange);
        syncInputAndRange(tenureInput, tenureRange);
        syncInputAndRange(interestRateInput, interestRateRange);

        const triggerDebouncedUpdate = debounce(calculateAndRender, 300);

        function formatNumberAbbreviation(num) {
            if (num >= 1_000_000) {
                return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
            } else if (num >= 1_000) {
                return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'k';
            } else {
                return num.toLocaleString();
            }
        }

        function calculateAndRender() {
            const principal = parseFloat(loanAmountInput.value);
            const rate = parseFloat(interestRateInput.value);
            const tenure = parseInt(tenureInput.value);

            if (!principal || !rate || !tenure) return;

            const monthlyRate = rate / 100 / 12;
            const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, tenure)) /
                (Math.pow(1 + monthlyRate, tenure) - 1);

            const totalPayment = emi * tenure;
            const totalInterest = totalPayment - principal;

            emiResult.innerHTML = `$${formatNumberAbbreviation(emi)} <small class="fs-6 fw-normal">/month</small>`;
            totalInterestEl.textContent = `$${formatNumberAbbreviation(totalInterest)}`;
            totalPaymentEl.textContent = `$${formatNumberAbbreviation(totalPayment)}`;
            principalAmountEl.textContent = `$${formatNumberAbbreviation(principal)}`;

            drawChart(principal, totalInterest);
        }

        function drawChart(principal, interest) {
            const ctx = document.getElementById('emiChart').getContext('2d');
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Principal', 'Interest'],
                    datasets: [{
                        data: [principal, interest],
                        backgroundColor: ['#00725b', '#960028']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {position: 'bottom'},
                        title: {display: false}
                    }
                }
            });
        }

        window.addEventListener('DOMContentLoaded', calculateAndRender);
    </script>
@endpush

@push('styles')
    <style>
        .height-dynamic {
            height: 470px !important;
        }
    </style>
@endpush
