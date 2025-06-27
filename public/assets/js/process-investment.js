// Initialize investment modal with plan data
function processInvestment(planId) {
    // Get the plan data
    const plan = getPlanData(planId);

    // Set up modal elements
    const modalElement = document.getElementById('processInvestmentModal');
    const modal = new bootstrap.Modal(modalElement);
    const investmentForm = document.getElementById('investmentForm');
    const confirmBtn = document.getElementById('confirmInvestmentBtn');
    const amountInput = document.getElementById('investment_amount');
    const projectedReturnEl = document.getElementById('projected_return_amount');
    const maturityDateEl = document.getElementById('maturity_date_display');
    const summaryTextEl = document.getElementById('investmentSummaryText');

    // Calculate maturity date (today and plan duration days)
    const maturityDate = new Date();
    maturityDate.setDate(maturityDate.getDate() + plan.duration_days);
    const maturityDateStr = maturityDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Set plan data in the modal
    summaryTextEl.textContent = `Investing in ${plan.name} with ${plan.interest_rate}% returns, paid ${plan.returns_period.toLowerCase()}`;
    maturityDateEl.textContent = maturityDateStr;
    document.getElementById('maturity_date_text').textContent = `Matures on ${maturityDateStr}`;

    // Store plan data in form (hidden fields or data attributes)
    investmentForm.setAttribute('data-plan-id', planId);

    // Reset form state
    amountInput.value = '';
    projectedReturnEl.textContent = '$0.00';
    document.getElementById('agree_terms').checked = false;
    document.getElementById('lock_funds').checked = false;
    confirmBtn.disabled = true;

    // Show modal
    modal.show();

    // Update projected return calculation
    const updateProjectedReturn = (amount, plan) => {
        if (!amount || isNaN(amount)) {
            projectedReturnEl.textContent = '$0.00';
            return;
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

        projectedReturnEl.textContent = '$' + projectedReturn.toFixed(2);
    };

    // Format currency input
    const formatCurrencyInput = (input) => {
        // Get raw value without currency symbols
        let value = input.value.replace(/[^\d.]/g, '');

        // Format as currency if there's a value
        if (value) {
            const num = parseFloat(value);
            if (!isNaN(num)) {
                updateProjectedReturn(num, plan);
            }
        } else {
            // Default empty state
            input.value = '';
            projectedReturnEl.textContent = '$0.00';
        }

        // Always validate after formatting
        validateForm();
    };

    // Show/hide amount validation feedback
    const showAmountFeedback = (isValid, isEmpty) => {
        let feedbackElement = document.getElementById('amount_feedback');

        // Create a feedback element if it doesn't exist
        if (!feedbackElement) {
            feedbackElement = document.createElement('div');
            feedbackElement.id = 'amount_feedback';
            feedbackElement.className = 'invalid-feedback d-block';
            feedbackElement.style.fontSize = '0.875rem';
            feedbackElement.style.marginTop = '0.25rem';
            amountInput.parentNode.appendChild(feedbackElement);
        }

        // Remove existing classes
        amountInput.classList.remove('is-invalid', 'is-valid');

        if (isEmpty) {
            // Hide feedback when input is empty
            feedbackElement.style.display = 'none';
        } else if (!isValid) {
            // Show error feedback
            amountInput.classList.add('is-invalid');
            feedbackElement.className = 'invalid-feedback d-block';
            feedbackElement.style.color = '#dc3545';
            feedbackElement.textContent = `Minimum investment amount is ${plan.min_amount.toLocaleString()}`;
            feedbackElement.style.display = 'block';
        } else {
            // Show success feedback
            amountInput.classList.add('is-valid');
            feedbackElement.className = 'valid-feedback d-block';
            feedbackElement.style.color = '#198754';
            feedbackElement.textContent = '✓ Amount meets minimum requirement';
            feedbackElement.style.display = 'block';
        }
    };

    // Form validation
    const validateForm = () => {
        const rawAmount = amountInput.value.replace(/[^\d.]/g, '');
        const amount = parseFloat(rawAmount);
        const agreeTerms = document.getElementById('agree_terms').checked;
        const lockFunds = document.getElementById('lock_funds').checked;

        // Check amount validity
        const isEmpty = !rawAmount;
        const isAmountValid = !isEmpty && !isNaN(amount) && amount >= plan.min_amount;

        // Show visual feedback for amount
        showAmountFeedback(isAmountValid, isEmpty, amount);

        // Check all conditions for form validity
        const isFormValid = isAmountValid && agreeTerms && lockFunds;

        if (isFormValid) {
            confirmBtn.disabled = false;
            return true;
        } else {
            confirmBtn.disabled = true;
            return false;
        }
    };

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();

        if (!validateForm()) return;

        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
        confirmBtn.disabled = true;

        const formData = new FormData(investmentForm);
        formData.append('plan_id', planId);
        formData.append('expected_profit', projectedReturnEl.textContent);
        formData.append('end_date', maturityDateStr);

        fetch('/investment/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    iziToast.success({
                        ...iziToastSettings,
                        message: data.message || 'Investment created successfully!'
                    });
                    modal.hide();
                    // Refresh or redirect as needed
                    setTimeout(() => window.location.href = data.redirect, 3000);
                } else {
                    throw new Error(data.message || 'Failed to create investment');
                }
            })
            .catch(error => {
                iziToast.error({
                    ...iziToastSettings,
                    message: error.message || 'An error occurred. Please try again.'
                });
                confirmBtn.innerHTML = 'Confirm Investment';
                confirmBtn.disabled = false;
            });
    };

    // Event listeners with proper references for cleanup
    const handleAmountInput = () => formatCurrencyInput(amountInput);
    const handleTermsChange = validateForm;
    const handleLockChange = validateForm;

    amountInput.addEventListener('input', handleAmountInput);
    document.getElementById('agree_terms').addEventListener('change', handleTermsChange);
    document.getElementById('lock_funds').addEventListener('change', handleLockChange);
    investmentForm.addEventListener('submit', handleSubmit);

    // Clean up when modal closes
    const cleanUp = () => {
        // Remove validation feedback
        const feedbackElement = document.getElementById('amount_feedback');
        if (feedbackElement) {
            feedbackElement.remove();
        }

        // Remove validation classes
        amountInput.classList.remove('is-invalid', 'is-valid');

        // Remove event listeners
        amountInput.removeEventListener('input', handleAmountInput);
        document.getElementById('agree_terms').removeEventListener('change', handleTermsChange);
        document.getElementById('lock_funds').removeEventListener('change', handleLockChange);
        investmentForm.removeEventListener('submit', handleSubmit);
        modalElement.removeEventListener('hidden.bs.modal', cleanUp);
    };

    modalElement.addEventListener('hidden.bs.modal', cleanUp);
}

// Helper function to get plan data
function getPlanData(planId) {
    const planElement = document.querySelector(`[data-plan-id="${planId}"]`);
    if (planElement) {
        return {
            id: planId,
            name: planElement.dataset.planName,
            interest_rate: parseFloat(planElement.dataset.interestRate),
            min_amount: parseFloat(planElement.dataset.minAmount),
            duration_days: parseInt(planElement.dataset.durationDays),
            liquidity: planElement.dataset.liquidity,
            returns_period: planElement.dataset.returnsPeriod || 'Monthly'
        };
    }
}

// Investment countdown
document.addEventListener('DOMContentLoaded', function() {
    const countdownElements = document.querySelectorAll('[id^="countdown"]');

    countdownElements.forEach(function(element) {
        const endDateStr = element.getAttribute('data-end-date');
        if (!endDateStr) return;

        const endDate = new Date(endDateStr).getTime();

        const interval = setInterval(() => {
            const now = new Date().getTime();
            const remaining = endDate - now;

            if (remaining > 0) {
                const days = Math.floor(remaining / (1000 * 60 * 60 * 24));
                const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
                element.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            } else {
                clearInterval(interval);
                element.textContent = 'Complete';
                element.classList.add('text-muted', 'fw-bold');
            }
        }, 1000);
    });
});
