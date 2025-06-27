// Loan Request
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('request-loan-form');
    const requestLoan = document.getElementById('requestLoan');
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

    const monthlyEmiInput = document.getElementById('monthlyEmiInput');
    const totalInterestInput = document.getElementById('totalInterestInput');
    const totalPaymentInput = document.getElementById('totalPaymentInput');

    const fields = ['title', 'loan_reason', 'loan_collateral', 'monthlyEmiInput', 'totalInterestInput', 'totalPaymentInput'];

    let chartInstance;

    function debounce(fn, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
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

    const triggerDebouncedUpdate = debounce(calculateAndRender, 500);

    function formatNumberAbbreviation(num) {
        if (isNaN(num)) return 'N/A';
        if (num >= 1_000_000) return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
        if (num >= 1_000) return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'k';
        return num.toLocaleString();
    }

    function calculateAndRender() {
        const principal = parseFloat(loanAmountInput.value);
        const rate = parseFloat(interestRateInput.value);
        const tenure = parseInt(tenureInput.value);

        if (isNaN(principal) || isNaN(rate) || isNaN(tenure) || principal <= 0 || rate < 0 || tenure <= 0) {
            emiResult.innerHTML = 'Invalid input <small class="fs-6 fw-normal">/month</small>';
            totalInterestEl.textContent = 'N/A';
            totalPaymentEl.textContent = 'N/A';
            principalAmountEl.textContent = 'N/A';
            if (chartInstance) chartInstance.destroy();
            return;
        }

        const monthlyRate = rate / 100 / 12;
        const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, tenure)) / (Math.pow(1 + monthlyRate, tenure) - 1);
        const totalPayment = emi * tenure;
        const totalInterest = totalPayment - principal;

        emiResult.innerHTML = `$${formatNumberAbbreviation(emi)} <small class="fs-6 fw-normal">/month</small>`;
        totalInterestEl.textContent = `$${formatNumberAbbreviation(totalInterest)}`;
        totalPaymentEl.textContent = `$${formatNumberAbbreviation(totalPayment)}`;
        principalAmountEl.textContent = `$${formatNumberAbbreviation(principal)}`;

        monthlyEmiInput.value = emi.toFixed(2);
        totalInterestInput.value = totalInterest.toFixed(2);
        totalPaymentInput.value = totalPayment.toFixed(2);

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
                    backgroundColor: [
                        getComputedStyle(document.documentElement).getPropertyValue('--theme-1').trim() || '#00725b',
                        getComputedStyle(document.documentElement).getPropertyValue('--theme-1-subtle').trim() || '#960028'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return `${context.label}: $${formatNumberAbbreviation(value)}`;
                            }
                        }
                    }
                }
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        requestLoan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        requestLoan.disabled = true;

        let isValid = true;

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (!input) return;
            const value = input.value.trim();

            if (value === '') {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            requestLoan.innerHTML = 'Submit Loan Request';
            requestLoan.disabled = false;
            return;
        }

        const formData = new FormData(form);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch("/loan/store", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    iziToast.success({
                        ...iziToastSettings,
                        message: data.message || 'Loan request submitted successfully!'
                    });
                    setTimeout(() => window.location.href = data.redirect, 3000);
                } else {
                    iziToast.error({
                        ...iziToastSettings,
                        message: data.message || 'Submission failed. Please check your inputs.'
                    });
                }
            })
            .catch(error => {
                iziToast.error({
                    ...iziToastSettings,
                    message: error.message || 'Something went wrong. Please try again.'
                });
                console.error('Error:', error);
            })
            .finally(() => {
                requestLoan.innerHTML = 'Submit Loan Request';
                requestLoan.disabled = false;
            });
    });

    // Clear validation styles on input
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('input', function () {
                input.classList.remove('is-invalid');
            });
        }
    });

    // Initial calculation
    calculateAndRender();
});
