function liquidateInvestment(investmentId) {
    // Get the investment data
    const investment = getInvestmentData(investmentId);

    if (!investment) {
        iziToast.error({
            ...iziToastSettings,
            message: 'Failed to load investment data. Please try again.'
        });
        return;
    }

    // Set up modal elements
    const modalElement = document.getElementById('liquidateInvestmentModal');
    const modal = new bootstrap.Modal(modalElement);
    const investmentForm = document.getElementById('liquidateForm');
    const confirmBtn = document.getElementById('confirmliquidationBtn');
    const amountInput = document.getElementById('investment_liquidation_amount');
    const projectedReturnEl = document.getElementById('investment_projected_return_amount');
    const maturityDateEl = document.getElementById('investment_maturity_date_display');
    const summaryTextEl = document.getElementById('liquidationSummaryText');
    const earlyLiquidationAlert = document.getElementById('early_liquidation_alert');

    // Hidden inputs
    const investmentIdInput = document.getElementById('investment_id_input');
    const liquidationAmountInput = document.getElementById('liquidation_amount_input');
    const isEarlyLiquidationInput = document.getElementById('is_early_liquidation_input');

    // Verify required elements exist
    if (!modalElement || !investmentForm || !confirmBtn || !projectedReturnEl ||
        !maturityDateEl || !summaryTextEl || !earlyLiquidationAlert ||
        !investmentIdInput || !liquidationAmountInput || !isEarlyLiquidationInput) {
        iziToast.error({
            ...iziToastSettings,
            message: 'Form initialization failed. Please refresh the page.'
        });
        return;
    }

    // Calculate profit and display
    const totalReturn = investment.investment_amount + investment.expected_profit;
    const isEarlyLiquidation = investment.remaining_time > 0;

    // Handle maturity date
    let maturityDate;
    try {
        maturityDate = new Date(investment.end_date);
        if (isNaN(maturityDate.getTime())) {
            throw new Error('Invalid date');
        }
    } catch (error) {
        console.error('Invalid end_date:', investment.end_date, error);
        maturityDate = new Date();
    }

    const maturityDateStr = maturityDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    if (amountInput) {
        amountInput.value = investment.investment_amount.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        });
    }

    projectedReturnEl.textContent = totalReturn.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
    });

    maturityDateEl.textContent = maturityDateStr;

    summaryTextEl.textContent = `Investment ID: ${investment.id} • Amount: ${investment.investment_amount.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
    })}`;

    // Set hidden input values
    investmentIdInput.value = investmentId;
    liquidationAmountInput.value = isEarlyLiquidation ? investment.investment_amount : totalReturn;
    isEarlyLiquidationInput.value = isEarlyLiquidation;

    // Show/hide early liquidation alert and checkboxes
    const confirmContainer = document.getElementById('confirm_liquidation_container');
    const acknowledgeContainer = document.getElementById('acknowledge_loss_container');
    const confirmLiquidationCheckbox = document.getElementById('confirm_liquidation_checkbox');
    const acknowledgeLossCheckbox = document.getElementById('acknowledge_loss_checkbox');

    // Initialize button state
    confirmBtn.disabled = isEarlyLiquidation; // Enable by default for mature investments

    if (isEarlyLiquidation) {
        if (!confirmLiquidationCheckbox || !acknowledgeLossCheckbox) {
            return;
        }

        earlyLiquidationAlert.innerHTML = `
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2 mt-1"></i>
                <div>
                    <strong>Early Liquidation Warning</strong><br>
                    You are attempting to liquidate before maturity. You will only receive your initial capital of
                    <strong>${investment.investment_amount.toLocaleString('en-US', { style: 'currency', currency: 'USD' })}</strong>
                    and lose all accumulated profit of
                    <strong>${investment.expected_profit.toLocaleString('en-US', { style: 'currency', currency: 'USD' })}</strong>.
                </div>
            </div>
        `;
        earlyLiquidationAlert.classList.remove('d-none');
        confirmContainer.classList.remove('d-none');
        acknowledgeContainer.classList.remove('d-none');

        // Update projected return for early liquidation
        projectedReturnEl.textContent = investment.investment_amount.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        });
        projectedReturnEl.classList.add('text-warning');

        // Reset checkbox states
        confirmLiquidationCheckbox.checked = false;
        acknowledgeLossCheckbox.checked = false;
    } else {
        earlyLiquidationAlert.classList.add('d-none');
        confirmContainer.classList.add('d-none');
        acknowledgeContainer.classList.add('d-none');
        projectedReturnEl.classList.remove('text-warning');
    }

    // Form validation function
    const validateForm = () => {
        if (isEarlyLiquidation) {
            const isFormValid = confirmLiquidationCheckbox.checked && acknowledgeLossCheckbox.checked;
            confirmBtn.disabled = !isFormValid;
            return isFormValid;
        }
        confirmBtn.disabled = false;
        return true;
    };

    // Add checkbox event listeners for early liquidation
    let checkboxCleanup = null;
    if (isEarlyLiquidation) {
        const handleCheckboxChange = () => validateForm();
        confirmLiquidationCheckbox.addEventListener('change', handleCheckboxChange);
        acknowledgeLossCheckbox.addEventListener('change', handleCheckboxChange);
        checkboxCleanup = () => {
            confirmLiquidationCheckbox.removeEventListener('change', handleCheckboxChange);
            acknowledgeLossCheckbox.removeEventListener('change', handleCheckboxChange);
        };
    }

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();

        if (isEarlyLiquidation && !validateForm()) {
            return;
        }

        confirmBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Processing...
        `;
        confirmBtn.disabled = true;

        const formData = new FormData(investmentForm);

        fetch(`/investment/${investmentId}/liquidate`, {
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
                        message: data.message || 'Investment liquidated successfully!'
                    });
                    modal.hide();
                    setTimeout(() => window.location.reload(), 3000);
                } else {
                    throw new Error(data.message || 'Failed to liquidate investment');
                }
            })
            .catch(error => {
                iziToast.error({
                    ...iziToastSettings,
                    message: error.message || 'An error occurred. Please try again.'
                });
                confirmBtn.innerHTML = 'Confirm Liquidation';
                validateForm(); // Restore correct button state
            });
    };

    // Add form submit listener
    investmentForm.addEventListener('submit', handleSubmit);

    // Clean up when modal closes
    const cleanUp = () => {
        if (checkboxCleanup) {
            checkboxCleanup();
        }

        // Reset form
        if (amountInput) amountInput.disabled = false;
        earlyLiquidationAlert.classList.add('d-none');
        confirmContainer.classList.add('d-none');
        acknowledgeContainer.classList.add('d-none');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Confirm Liquidation';

        // Remove listeners
        investmentForm.removeEventListener('submit', handleSubmit);
        modalElement.removeEventListener('hidden.bs.modal', cleanUp);
    };

    modalElement.addEventListener('hidden.bs.modal', cleanUp);

    // Show modal with proper initial state
    requestAnimationFrame(() => {
        validateForm();
        modal.show();
    });
}

// Helper function to get investment data
function getInvestmentData(investmentId) {
    const investmentElement = document.querySelector(`[data-investment-id="${investmentId}"]`);

    if (investmentElement) {
        return {
            id: investmentId,
            investment_amount: parseFloat(investmentElement.dataset.investmentAmount) || 0,
            expected_profit: parseFloat(investmentElement.dataset.investmentProfit) || 0,
            remaining_time: parseFloat(investmentElement.dataset.remainingTime) || 0,
            end_date: investmentElement.dataset.endDate || new Date().toISOString()
        };
    }
    return null;
}
